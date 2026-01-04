<?php

namespace Core;

/**
 * Error Handler - Manages application errors and exceptions
 * 
 * Features:
 *   - Custom error pages (404, 500, etc.)
 *   - Error logging
 *   - Debug mode support
 */
class ErrorHandler
{
    private static bool $initialized = false;

    /**
     * Initialize error and exception handling
     */
    public static function initialize(): void
    {
        if (self::$initialized) {
            return;
        }

        // Set error handler
        set_error_handler([self::class, 'handleError']);

        // Set exception handler
        set_exception_handler([self::class, 'handleException']);

        // Set shutdown handler for fatal errors
        register_shutdown_function([self::class, 'handleShutdown']);

        self::$initialized = true;
    }

    /**
     * Handle PHP errors
     */
    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool {
        $isDebug = Config::isDebug();

        // Log error
        Logger::error("[$errno] $errstr in $errfile:$errline");

        if (!$isDebug) {
            // In production, don't show errors
            http_response_code(500);
            self::show500();
            exit;
        }

        return false; // Let default handler run in debug mode
    }

    /**
     * Handle exceptions
     */
    public static function handleException(\Throwable $exception): void
    {
        $isDebug = Config::isDebug();

        // Log exception
        Logger::error($exception->getMessage() . "\n" . $exception->getTraceAsString());

        http_response_code(500);

        if ($isDebug) {
            self::showDebugError($exception);
        } else {
            self::show500();
        }

        exit;
    }

    /**
     * Handle fatal errors on shutdown
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * Show 404 page
     */
    public static function show404(): void
    {
        http_response_code(404);
        self::showErrorPage(404, 'Page Non Trouvée', 'La page que vous recherchez n\'existe pas.');
    }

    /**
     * Show 500 error page
     */
    public static function show500(): void
    {
        http_response_code(500);
        self::showErrorPage(500, 'Erreur Serveur', 'Une erreur interne s\'est produite.');
    }

    /**
     * Show generic error page
     */
    public static function showErrorPage(int $code, string $title, string $message): void
    {
?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $code; ?> - <?php echo htmlspecialchars($title); ?></title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .error-container {
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                    padding: 40px;
                    max-width: 600px;
                    text-align: center;
                }

                .error-code {
                    font-size: 5em;
                    font-weight: bold;
                    color: #667eea;
                    margin-bottom: 20px;
                }

                .error-title {
                    font-size: 2em;
                    color: #333;
                    margin-bottom: 15px;
                }

                .error-message {
                    color: #666;
                    font-size: 1.1em;
                    margin-bottom: 30px;
                    line-height: 1.6;
                }

                .error-actions {
                    display: flex;
                    gap: 10px;
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    border-radius: 5px;
                    text-decoration: none;
                    font-weight: bold;
                    transition: all 0.3s;
                }

                .btn-primary {
                    background: #667eea;
                    color: white;
                }

                .btn-primary:hover {
                    background: #5568d3;
                    transform: translateY(-2px);
                }

                .btn-secondary {
                    background: #f0f0f0;
                    color: #333;
                }

                .btn-secondary:hover {
                    background: #e0e0e0;
                }
            </style>
        </head>

        <body>
            <div class="error-container">
                <div class="error-code"><?php echo $code; ?></div>
                <h1 class="error-title"><?php echo htmlspecialchars($title); ?></h1>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
                <div class="error-actions">
                    <a href="/" class="btn btn-primary">🏠 Accueil</a>
                    <a href="javascript:history.back()" class="btn btn-secondary">← Retour</a>
                </div>
            </div>
        </body>

        </html>
    <?php
    }

    /**
     * Show debug error with full information
     */
    private static function showDebugError(\Throwable $exception): void
    {
    ?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Debug - <?php echo htmlspecialchars(get_class($exception)); ?></title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Courier New', monospace;
                    background: #1e1e1e;
                    color: #d4d4d4;
                    padding: 20px;
                }

                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                }

                h1 {
                    color: #f48771;
                    margin-bottom: 20px;
                    font-size: 2em;
                }

                .section {
                    background: #252526;
                    border: 1px solid #3e3e42;
                    border-radius: 5px;
                    padding: 20px;
                    margin-bottom: 20px;
                }

                .section h2 {
                    color: #4ec9b0;
                    margin-bottom: 10px;
                    font-size: 1.3em;
                }

                .trace {
                    background: #1e1e1e;
                    padding: 15px;
                    border-left: 3px solid #f48771;
                    margin: 10px 0;
                    overflow-x: auto;
                }

                .trace-line {
                    padding: 5px;
                    color: #ce9178;
                }

                .file {
                    color: #9cdcfe;
                }

                .line {
                    color: #b5cea8;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <h1>🔴 <?php echo htmlspecialchars(get_class($exception)); ?></h1>

                <div class="section">
                    <h2>Message</h2>
                    <p><?php echo htmlspecialchars($exception->getMessage()); ?></p>
                </div>

                <div class="section">
                    <h2>Location</h2>
                    <p>
                        <span class="file"><?php echo htmlspecialchars($exception->getFile()); ?></span>
                        : <span class="line"><?php echo $exception->getLine(); ?></span>
                    </p>
                </div>

                <div class="section">
                    <h2>Stack Trace</h2>
                    <?php
                    $traces = $exception->getTrace();
                    foreach ($traces as $i => $trace) {
                        $file = $trace['file'] ?? 'Unknown';
                        $line = $trace['line'] ?? 'Unknown';
                        $function = $trace['function'] ?? 'Unknown';
                        $class = $trace['class'] ?? '';
                    ?>
                        <div class="trace">
                            <div class="trace-line">
                                <strong>#<?php echo $i; ?></strong>
                                <span class="file"><?php echo htmlspecialchars($file); ?></span>
                                (<span class="line"><?php echo $line; ?></span>):
                                <?php echo htmlspecialchars($class ? "$class::$function()" : "$function()"); ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </body>

        </html>
<?php
    }
}
