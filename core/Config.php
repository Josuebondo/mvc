<?php

namespace Core;

/**
 * Configuration Manager - Handles .env files and environment variables
 * 
 * Usage:
 *   Config::get('APP_NAME')
 *   Config::get('DB_HOST', 'localhost') // with default
 */
class Config
{
    private static array $config = [];
    private static bool $loaded = false;

    /**
     * Load configuration from .env file
     */
    public static function load(string $path = ''): void
    {
        if (self::$loaded) {
            return;
        }

        if (empty($path)) {
            $path = dirname(__DIR__) . '/.env';
        }

        // Load from .env file
        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Skip comments
                if (str_starts_with(trim($line), '#')) {
                    continue;
                }

                if (strpos($line, '=') === false) {
                    continue;
                }

                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))
                ) {
                    $value = substr($value, 1, -1);
                }

                self::$config[$key] = $value;
            }
        }

        // Also load environment variables
        foreach ($_ENV as $key => $value) {
            self::$config[$key] = $value;
        }

        self::$loaded = true;
    }

    /**
     * Get configuration value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config[$key] ?? $default;
    }

    /**
     * Set configuration value
     */
    public static function set(string $key, mixed $value): void
    {
        self::$config[$key] = $value;
    }

    /**
     * Check if key exists
     */
    public static function has(string $key): bool
    {
        if (!self::$loaded) {
            self::load();
        }

        return isset(self::$config[$key]);
    }

    /**
     * Get all configuration
     */
    public static function all(): array
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$config;
    }

    /**
     * Check if application is in debug mode
     */
    public static function isDebug(): bool
    {
        return self::get('APP_DEBUG', false) === 'true' || self::get('APP_DEBUG', false) === true;
    }

    /**
     * Get application environment (development, production, testing)
     */
    public static function getEnv(): string
    {
        return self::get('APP_ENV', 'development');
    }

    /**
     * Get application name
     */
    public static function getAppName(): string
    {
        return self::get('APP_NAME', 'BondoMVC');
    }

    /**
     * Get application URL
     */
    public static function getAppUrl(): string
    {
        return self::get('APP_URL', 'http://localhost');
    }
}
