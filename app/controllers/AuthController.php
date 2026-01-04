<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Validator;
use App\Models\UserModel;

/**
 * Authentication Controller
 * Gère login, register, logout
 */
class AuthController extends Controller
{
    protected UserModel $userModel;

    public function __construct()
    {
        Auth::init();
        $this->userModel = new UserModel();
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function login()
    {
        // Rediriger si déjà connecté
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $this->view('auth/login');
    }

    /**
     * Traiter la connexion
     */
    public function doLogin()
    {
        if (!isPost()) {
            redirect('/auth/login');
        }

        $email = getInput('email');
        $password = getInput('password');

        // Valider
        $validator = new Validator([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->hasErrors()) {
            setSession('errors', $validator->errors());
            redirect('/auth/login');
        }

        // Essayer de connecter
        if (Auth::attempt($email, $password)) {
            setSession('success', 'Connexion réussie!');
            redirect('/dashboard');
        } else {
            setSession('error', 'Email ou mot de passe incorrect');
            redirect('/auth/login');
        }
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function register()
    {
        // Rediriger si déjà connecté
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $this->view('auth/register');
    }

    /**
     * Traiter l'inscription
     */
    public function doRegister()
    {
        if (!isPost()) {
            redirect('/auth/register');
        }

        $name = getInput('name');
        $email = getInput('email');
        $password = getInput('password');
        $confirm = getInput('password_confirm');

        // Valider
        $validator = new Validator([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirm' => $confirm
        ], [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirm' => 'required|confirmed:password'
        ]);

        if ($validator->hasErrors()) {
            setSession('errors', $validator->errors());
            redirect('/auth/register');
        }

        // Vérifier l'email
        if ($this->userModel->emailExists($email)) {
            setSession('error', 'Cet email est déjà utilisé');
            redirect('/auth/register');
        }

        // Créer l'utilisateur
        if (Auth::register($name, $email, $password)) {
            setSession('success', 'Inscription réussie! Bienvenue ' . $name);
            redirect('/dashboard');
        } else {
            setSession('error', 'Une erreur est survenue lors de l\'inscription');
            redirect('/auth/register');
        }
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout()
    {
        Auth::logout();
        setSession('success', 'Déconnexion réussie');
        redirect('/');
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function forgotPassword()
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $this->view('auth/forgot-password');
    }

    /**
     * Traiter la réinitialisation
     */
    public function doResetPassword()
    {
        if (!isPost()) {
            redirect('/auth/forgot-password');
        }

        $email = getInput('email');

        if (!$email) {
            setSession('error', 'Email requis');
            redirect('/auth/forgot-password');
        }

        if (!$this->userModel->emailExists($email)) {
            // Ne pas révéler si l'email existe ou non
            setSession('success', 'Si cet email existe, vous recevrez un lien');
            redirect('/auth/forgot-password');
        }

        // TODO: Envoyer un email avec un lien de réinitialisation

        setSession('success', 'Un email de réinitialisation a été envoyé');
        redirect('/');
    }
}
