#!/usr/bin/env php

<?php
/**
 * Migration CLI Handler
 * Add to artisan file
 */

if (php_sapi_name() !== 'cli') {
    die('Migration commands can only be run from CLI');
}

require __DIR__ . '/config/config.php';

$action = $argv[1] ?? null;

if (!$action || in_array($action, ['--help', '-h'])) {
    echo "\n📊 Database Migrations\n";
    echo "=====================\n\n";
    echo "php artisan migrate              Run pending migrations\n";
    echo "php artisan migrate:rollback     Rollback last migration batch\n";
    echo "php artisan migrate:refresh      Rollback all and re-run\n";
    echo "php artisan make:migration Name  Create a new migration\n\n";
    exit(0);
}

use Core\Migration;

$migration = new Migration();

switch ($action) {
    case 'migrate':
        $migration->runMigrations();
        break;
    
    case 'migrate:rollback':
        $migration->rollback();
        break;
    
    case 'migrate:refresh':
        echo "Refreshing migrations...\n";
        // Rollback all first
        while (true) {
            try {
                $migration->rollback();
            } catch (\Exception $e) {
                break;
            }
        }
        // Then run all
        $migration->runMigrations();
        break;
    
    case 'make:migration':
        $name = $argv[2] ?? null;
        if (!$name) {
            echo "❌ Usage: php artisan make:migration MigrationName\n";
            exit(1);
        }
        createMigration($name);
        break;
    
    default:
        echo "❌ Unknown migration command: $action\n";
        exit(1);
}

function createMigration($name)
{
    $timestamp = date('Y_m_d_His');
    $filename = "{$timestamp}_{$name}.php";
    $path = __DIR__ . "/database/migrations/{$filename}";

    $className = str_replace('_', '', ucwords($name, '_'));

    $stub = <<<PHP
<?php

namespace Database\Migrations;

use Core\Database;

/**
 * Migration: {$name}
 */
class {$className} extends Migration
{
    public function up(Database \$db)
    {
        // TODO: Create your table
        // \$sql = "CREATE TABLE table_name (...)";
        // \$db->getConnection()->exec(\$sql);
    }

    public function down(Database \$db)
    {
        // TODO: Drop your table
        // \$db->getConnection()->exec("DROP TABLE IF EXISTS table_name");
    }
}
PHP;

    file_put_contents($path, $stub);
    echo "✓ Migration created: database/migrations/{$filename}\n";
    echo "💡 Edit the file and run: php artisan migrate\n";
}
?>
