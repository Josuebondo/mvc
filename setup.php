#!/usr/bin/env php
<?php
/**
 * BondoMVC Setup Script
 * Configure le projet après téléchargement
 */

echo "\n🚀 BondoMVC Setup\n";
echo "=================\n\n";

// 1. Créer le fichier .env
if (!file_exists('.env')) {
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "✓ Fichier .env créé\n";
    } else {
        echo "⚠ .env.example introuvable\n";
    }
} else {
    echo "✓ .env existe déjà\n";
}

// 2. Créer les dossiers nécessaires
$dirs = [
    'storage/uploads' => 'storage/uploads',
    'storage/logs' => 'storage/logs',
    'public/uploads' => 'public/uploads'
];

foreach ($dirs as $dir => $display) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
        echo "✓ Dossier $display créé\n";
    }
}

// 3. Vérifier les permissions
echo "\n📋 Vérifications:\n";
echo "✓ PHP " . PHP_VERSION . "\n";

if (file_exists('composer.json')) {
    echo "✓ composer.json trouvé\n";
}

if (file_exists('config/config.php')) {
    echo "✓ Configuration trouvée\n";
}

if (file_exists('index.php')) {
    echo "✓ index.php trouvé\n";
}

echo "\n✅ Setup terminé!\n\n";
echo "Prochaines étapes:\n";
echo "1. Configurer .env (base de données, etc.)\n";
echo "2. Créer vos contrôleurs: php console make:controller NomController\n";
echo "3. Créer vos modèles: php console make:model NomModel\n";
echo "4. Lancer: php -S localhost:8000\n\n";
?>