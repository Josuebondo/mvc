<?php

/**
 * Helpers - Fonctions utilitaires globales
 * Ces fonctions sont dans le namespace global pour être accessibles partout
 */

/**
 * Afficher et arrêter (die dump)
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Afficher sans arrêter
 */
function dump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

/**
 * Redirection
 */
function redirect($url = '/')
{
    header('Location: ' . url($url));
    exit;
}

/**
 * Récupérer une valeur de $_GET ou POST
 */
function getInput($key, $default = null)
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/**
 * Vérifier si la requête est POST
 */
function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Vérifier si la requête est GET
 */
function isGet(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Vérifier si la requête est AJAX
 */
function isAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Sécuriser l'affichage HTML
 */
function escape($data): string
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Obtenir l'URL racine de l'application
 */
function url($path = ''): string
{
    return URLROOT . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Obtenir l'URL publique (assets)
 */
function asset($path): string
{
    return url('/public/' . ltrim($path, '/'));
}

/**
 * Afficher un message de session
 */
function session($key = null)
{
    if ($key === null) {
        return $_SESSION;
    }
    return $_SESSION[$key] ?? null;
}

/**
 * Définir un message de session
 */
function setSession($key, $value): void
{
    $_SESSION[$key] = $value;
}

/**
 * Supprimer une session
 */
function unsetSession($key): void
{
    unset($_SESSION[$key]);
}

/**
 * Vérifier si l'utilisateur est authentifié
 */
function isAuth(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Obtenir l'utilisateur authentifié
 */
function auth()
{
    return $_SESSION['user'] ?? null;
}

/**
 * Valider un email
 */
function isValidEmail($email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Générer un CSRF token
 */
function generateCsrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifier un CSRF token
 */
function verifyCsrfToken($token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
