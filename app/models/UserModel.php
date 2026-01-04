<?php

namespace App\Models;

use Core\Model;

/**
 * User Model
 * Gère les opérations utilisateur
 */
class UserModel extends Model
{
    protected string $table = 'users';

    /**
     * Récupérer un utilisateur par email
     */
    public function getByEmail(string $email): ?array
    {
        $result = $this->db()->fetch(
            "SELECT * FROM {$this->table} WHERE email = ?",
            [$email]
        );

        return $result ?: null;
    }

    /**
     * Récupérer par ID
     */
    public function getById(int $id): ?array
    {
        $result = $this->db()->fetch(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );

        return $result ?: null;
    }

    /**
     * Créer un utilisateur
     */
    public function create(array $data): ?int
    {
        return $this->db()->insert($this->table, $data);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(int $id, array $data): bool
    {
        return $this->db()->update($this->table, $data, ['id' => $id]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->table, ['id' => $id]);
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getAll(): array
    {
        return $this->db()->fetchAll("SELECT * FROM {$this->table}");
    }

    /**
     * Vérifier le mot de passe
     */
    public function verifyPassword(int $userId, string $password): bool
    {
        $user = $this->getById($userId);

        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password']);
    }

    /**
     * Vérifier l'email unique
     */
    public function emailExists(string $email): bool
    {
        return $this->getByEmail($email) !== null;
    }

    /**
     * Paginer les utilisateurs
     */
    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;

        $users = $this->db()->fetchAll(
            "SELECT * FROM {$this->table} LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        $total = $this->db()->fetch("SELECT COUNT(*) as count FROM {$this->table}");

        return [
            'users' => $users,
            'total' => $total['count'],
            'page' => $page,
            'perPage' => $perPage,
            'pages' => ceil($total['count'] / $perPage)
        ];
    }
}
