#!/usr/bin/env php
<?php
/**
 * Installation Checker - Verify BondoMVC installation
 * 
 * Usage:
 *   php check-installation.php
 */

echo "\n🔍 BondoMVC Installation Checker\n";
echo str_repeat("=", 50) . "\n\n";

$checks = [];
$errors = [];

// 1. Check PHP version
echo "1️⃣  Checking PHP version... ";
$phpVersion = phpversion();
if (version_compare($phpVersion, '8.0.0', '>=')) {
    echo "✅ PHP {$phpVersion}\n";
    $checks['php'] = true;
} else {
    echo "❌ PHP {$phpVersion} (Required: 8.0+)\n";
    $errors[] = "PHP version too old";
}

// 2. Check required directories
echo "2️⃣  Checking directories... ";
$dirs = [
    'app' => __DIR__ . '/app',
    'config' => __DIR__ . '/config',
    'core' => __DIR__ . '/core',
    'database' => __DIR__ . '/database',
    'public' => __DIR__ . '/public',
    'storage' => __DIR__ . '/storage',
    'vendor' => __DIR__ . '/vendor',
];

$allDirsExist = true;
foreach ($dirs as $name => $path) {
    if (!is_dir($path)) {
        $allDirsExist = false;
        $errors[] = "Missing directory: {$name}";
    }
}

if ($allDirsExist) {
    echo "✅ All directories present\n";
    $checks['dirs'] = true;
} else {
    echo "❌ Some directories missing\n";
}

// 3. Check composer
echo "3️⃣  Checking Composer... ";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ Composer installed\n";
    $checks['composer'] = true;
} else {
    echo "❌ Run: composer install\n";
    $errors[] = "Composer not installed";
}

// 4. Check .env file
echo "4️⃣  Checking .env file... ";
if (file_exists(__DIR__ . '/.env')) {
    echo "✅ .env exists\n";
    $checks['env'] = true;

    // Check .env content
    $env = parse_ini_file(__DIR__ . '/.env');

    // Check required vars
    $requiredVars = ['APP_NAME', 'DB_HOST', 'DB_NAME', 'DB_USER'];
    $missingVars = [];

    foreach ($requiredVars as $var) {
        if (empty($env[$var])) {
            $missingVars[] = $var;
        }
    }

    if ($missingVars) {
        echo "   ⚠️  Missing .env variables: " . implode(', ', $missingVars) . "\n";
    }
} else {
    echo "❌ Create .env from .env.example\n";
    $errors[] = ".env file not found";
}

// 5. Check database connection
echo "5️⃣  Checking Database... ";
if (isset($env['DB_HOST'], $env['DB_NAME'], $env['DB_USER'])) {
    try {
        $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4";
        $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'] ?? '');
        echo "✅ Database connected\n";
        $checks['db'] = true;
    } catch (PDOException $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "\n";
        $errors[] = "Database connection failed";
    }
} else {
    echo "⏭️  Skipped (missing .env)\n";
}

// 6. Check writable directories
echo "6️⃣  Checking permissions... ";
$writableDirs = [
    'storage' => __DIR__ . '/storage',
    'storage/logs' => __DIR__ . '/storage/logs',
    'storage/uploads' => __DIR__ . '/storage/uploads',
    'app/views' => __DIR__ . '/app/views',
];

$allWritable = true;
foreach ($writableDirs as $name => $path) {
    if (!is_writable($path)) {
        $allWritable = false;
        $errors[] = "Not writable: {$name}";
    }
}

if ($allWritable) {
    echo "✅ All directories writable\n";
    $checks['perms'] = true;
} else {
    echo "❌ Some directories not writable\n";
}

// 7. Check key files
echo "7️⃣  Checking files... ";
$files = [
    'index.php' => __DIR__ . '/index.php',
    'artisan' => __DIR__ . '/artisan',
    'composer.json' => __DIR__ . '/composer.json',
];

$allFilesExist = true;
foreach ($files as $name => $path) {
    if (!file_exists($path)) {
        $allFilesExist = false;
        $errors[] = "Missing file: {$name}";
    }
}

if ($allFilesExist) {
    echo "✅ All key files present\n";
    $checks['files'] = true;
} else {
    echo "❌ Some files missing\n";
}

// 8. Check extensions
echo "8️⃣  Checking PHP extensions... ";
$extensions = ['pdo', 'pdo_mysql', 'json', 'fileinfo'];
$missingExt = [];

foreach ($extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExt[] = $ext;
    }
}

if (empty($missingExt)) {
    echo "✅ All extensions loaded\n";
    $checks['ext'] = true;
} else {
    echo "❌ Missing: " . implode(', ', $missingExt) . "\n";
    $errors[] = "Missing PHP extensions";
}

// Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 Summary\n";
echo str_repeat("=", 50) . "\n\n";

$passed = count($checks);
$total = 8;

echo "Checks passed: {$passed}/{$total}\n\n";

if (!empty($errors)) {
    echo "❌ Issues found:\n";
    foreach ($errors as $error) {
        echo "  • {$error}\n";
    }
    echo "\n📖 See INSTALL_MULTIPC.md for solutions\n";
} else {
    echo "✅ Everything looks good!\n\n";
    echo "Next steps:\n";
    echo "  1. php artisan serve\n";
    echo "  2. Visit http://localhost:8000\n";
    echo "  3. Start coding! 🚀\n";
}

echo "\n";
exit(empty($errors) ? 0 : 1);
