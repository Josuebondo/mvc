<?php

namespace Core;

use App\Models\UserModel;

/**
 * Authentication Service
 * Gère l'authentification utilisateur
 */
class Auth
{
    protected static ?array $user = null;
    protected static UserModel $userModel;

    /**
     * Initialiser le service d'auth
     */
    public static function init()
    {
        self::$userModel = new UserModel();

        // Récupérer l'utilisateur en session
        if (isset($_SESSION['user_id'])) {
            self::$user = self::$userModel->getById($_SESSION['user_id']);
        }
    }

    /**
     * Vérifier les credentials et connecter
     */
    public static function attempt(string $email, string $password): bool
    {
        $user = self::$userModel->getByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Connecter l'utilisateur
        self::login($user);
        return true;
    }

    /**
     * Connecter manuellement un utilisateur
     */
    public static function login(array $user)
    {
        $_SESSION['user_id'] = $user['id'];
        self::$user = $user;
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public static function register(string $name, string $email, string $password): bool
    {
        // Vérifier si l'email existe
        if (self::$userModel->getByEmail($email)) {
            return false;
        }

        // Créer l'utilisateur
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $userId = self::$userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        if ($userId) {
            $user = self::$userModel->getById($userId);
            self::login($user);
            return true;
        }

        return false;
    }

    /**
     * Déconnecter l'utilisateur
     */
    public static function logout()
    {
        unset($_SESSION['user_id']);
        self::$user = null;
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public static function check(): bool
    {
        return self::$user !== null;
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public static function user(): ?array
    {
        return self::$user;
    }

    /**
     * Récupérer l'ID de l'utilisateur
     */
    public static function id(): ?int
    {
        return self::$user['id'] ?? null;
    }

    /**
     * Vérifier les permissions
     */
    public static function hasPermission(string $permission): bool
    {
        if (!self::check()) {
            return false;
        }

        // TODO: Implémenter les permissions
        return true;
    }

    /**
     * Vérifier les rôles
     */
    public static function hasRole(string $role): bool
    {
        if (!self::check()) {
            return false;
        }

        // TODO: Implémenter les rôles
        return self::$user['role'] === $role;
    }

    /**
     * Vérifier l'email
     */
    public static function verifyEmail(string $email): bool
    {
        // TODO: Implémenter la vérification d'email
        return true;
    }

    /**
     * Réinitialiser le mot de passe
     */
    public static function resetPassword(string $email, string $newPassword): bool
    {
        $user = self::$userModel->getByEmail($email);

        if (!$user) {
            return false;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        return self::$userModel->update($user['id'], [
            'password' => $hashedPassword
        ]);
    }
}
