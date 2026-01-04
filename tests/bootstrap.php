<?php

/**
 * PHPUnit Bootstrap File
 * Configure l'environnement de test
 */

// Charge les dépendances
require __DIR__ . '/../vendor/autoload.php';

// Charge le config
require __DIR__ . '/../config/config.php';

// Définir l'environnement de test
putenv('APP_ENV=testing');
putenv('DB_NAME=bondomvc_test');
