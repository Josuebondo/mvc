<?php

namespace Core;

/**
 * Classe Middleware - Base pour les middlewares
 */
abstract class Middleware
{
    /**
     * Exécuter le middleware
     * Retourner true pour continuer, false pour arrêter
     */
    abstract public function handle(): bool;

    /**
     * Vérifier que l'utilisateur est authentifié
     */
    protected function isAuthenticated(): bool
    {
        return isAuth();
    }

    /**
     * Obtenir l'utilisateur actuel
     */
    protected function getUser()
    {
        return auth();
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect($url)
    {
        redirect($url);
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
