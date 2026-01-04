<?php

// config/config.php
// Configuration settings for the application.
// Load from .env file if available, otherwise use defaults

// Load .env file
$envFile = dirname(dirname(__FILE__)) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, '\'"');
            $_ENV[$key] = $value;
        }
    }
}

// Get config from .env or use defaults
$getEnv = fn($key, $default = '') => $_ENV[$key] ?? getenv($key) ?: $default;

// APPLICATION SETTINGS
define('APP_NAME', $getEnv('APP_NAME', 'BondoMVC'));
define('APP_ENV', $getEnv('APP_ENV', 'development'));
define('APP_DEBUG', $getEnv('APP_DEBUG', 'true') === 'true');

// URL SETTINGS
define('URLROOT', $getEnv('URLROOT', 'http://localhost/BondoMVC'));
define('APPROOT', dirname(dirname(__FILE__)));
define('LAYOUT', URLROOT . '/layouts');

// DATABASE SETTINGS
define('DB_HOST', $getEnv('DB_HOST', 'localhost'));
define('DB_NAME', $getEnv('DB_NAME', 'bondo'));
define('DB_USER', $getEnv('DB_USER', 'root'));
define('DB_PASS', $getEnv('DB_PASS', ''));
define('DB_CHARSET', $getEnv('DB_CHARSET', 'utf8mb4'));

// SESSION SETTINGS
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
