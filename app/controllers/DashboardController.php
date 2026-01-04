<?php

namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Middleware\AuthMiddleware;

/**
 * Dashboard Controller
 * Pages protégées
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        Auth::init();

        if (!Auth::check()) {
            redirect('/auth/login');
        }
    }

    /**
     * Tableau de bord
     */
    public function index()
    {
        $user = Auth::user();

        $this->view('dashboard/index', [
            'user' => $user
        ]);
    }

    /**
     * Profil utilisateur
     */
    public function profile()
    {
        $user = Auth::user();

        $this->view('dashboard/profile', [
            'user' => $user
        ]);
    }

    /**
     * Éditer le profil
     */
    public function editProfile()
    {
        $user = Auth::user();

        $this->view('dashboard/edit-profile', [
            'user' => $user
        ]);
    }

    /**
     * Mettre à jour le profil
     */
    public function doUpdateProfile()
    {
        if (!isPost()) {
            redirect('/dashboard/profile');
        }

        $userId = Auth::id();
        $name = getInput('name');
        $email = getInput('email');

        // TODO: Valider et mettre à jour

        setSession('success', 'Profil mis à jour');
        redirect('/dashboard/profile');
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword()
    {
        $this->view('dashboard/change-password');
    }

    /**
     * Traiter le changement de mot de passe
     */
    public function doChangePassword()
    {
        if (!isPost()) {
            redirect('/dashboard/change-password');
        }

        $userId = Auth::id();
        $current = getInput('current_password');
        $new = getInput('new_password');
        $confirm = getInput('password_confirm');

        // TODO: Valider et changer le mot de passe

        setSession('success', 'Mot de passe changé');
        redirect('/dashboard');
    }
}
