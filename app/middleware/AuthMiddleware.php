<?php

namespace App\Middleware;

use Core\Middleware;
use Core\Auth;

/**
 * Auth Middleware
 * Protège les routes qui nécessitent l'authentification
 */
class AuthMiddleware extends Middleware
{
    public function handle(): bool
    {
        Auth::init();

        if (!Auth::check()) {
            $this->redirect('/auth/login');
            return false;
        }

        return true;
    }
}
