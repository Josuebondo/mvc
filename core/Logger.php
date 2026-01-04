<?php

namespace Core;

/**
 * Logger - Simple logging system
 * 
 * Features:
 *   - Multiple log levels (debug, info, warning, error, critical)
 *   - File-based logging
 *   - Timestamped messages
 * 
 * Usage:
 *   Logger::info('User logged in');
 *   Logger::error('Database connection failed');
 *   Logger::warning('Cache miss on expensive query');
 */
class Logger
{
    private const LOG_DIR = __DIR__ . '/../storage/logs';

    private const LEVELS = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4,
    ];

    /**
     * Log a message
     */
    private static function log(string $level, string $message, array $context = []): void
    {
        // Create logs directory if it doesn't exist
        if (!is_dir(self::LOG_DIR)) {
            mkdir(self::LOG_DIR, 0755, true);
        }

        // Format message with context
        $formattedMessage = self::formatMessage($message, $context);

        // Get log file path
        $date = date('Y-m-d');
        $logFile = self::LOG_DIR . "/$date.log";

        // Format log entry
        $timestamp = date('H:i:s');
        $logEntry = "[$timestamp] [$level] $formattedMessage" . PHP_EOL;

        // Write to file
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }

    /**
     * Format message with context variables
     */
    private static function formatMessage(string $message, array $context = []): string
    {
        if (empty($context)) {
            return $message;
        }

        // Replace {key} with context values
        foreach ($context as $key => $value) {
            $placeholder = "{" . $key . "}";
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            $message = str_replace($placeholder, (string)$value, $message);
        }

        return $message;
    }

    /**
     * Log debug message
     */
    public static function debug(string $message, array $context = []): void
    {
        if (Config::isDebug()) {
            self::log('DEBUG', $message, $context);
        }
    }

    /**
     * Log info message
     */
    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log warning message
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log error message
     */
    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log critical message
     */
    public static function critical(string $message, array $context = []): void
    {
        self::log('CRITICAL', $message, $context);
    }

    /**
     * Get logs from a specific date
     */
    public static function getLogs(string $date = ''): array
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $logFile = self::LOG_DIR . "/$date.log";

        if (!file_exists($logFile)) {
            return [];
        }

        $logs = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (preg_match('/\[(\d{2}:\d{2}:\d{2})\] \[([A-Z]+)\] (.+)/', $line, $matches)) {
                $logs[] = [
                    'time' => $matches[1],
                    'level' => $matches[2],
                    'message' => $matches[3],
                ];
            }
        }

        return $logs;
    }

    /**
     * Get all log files
     */
    public static function getLogFiles(): array
    {
        if (!is_dir(self::LOG_DIR)) {
            return [];
        }

        $files = [];
        $logFiles = glob(self::LOG_DIR . '/*.log');

        foreach ($logFiles as $file) {
            $files[] = [
                'name' => basename($file),
                'date' => str_replace('.log', '', basename($file)),
                'size' => filesize($file),
                'modified' => filemtime($file),
            ];
        }

        // Sort by date descending
        usort($files, fn($a, $b) => $b['modified'] <=> $a['modified']);

        return $files;
    }

    /**
     * Clear logs
     */
    public static function clearOldLogs(int $days = 7): int
    {
        if (!is_dir(self::LOG_DIR)) {
            return 0;
        }

        $deleted = 0;
        $cutoff = time() - ($days * 24 * 60 * 60);

        foreach (glob(self::LOG_DIR . '/*.log') as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
                $deleted++;
            }
        }

        return $deleted;
    }
}
