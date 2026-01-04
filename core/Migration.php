<?php

namespace Core;

/**
 * Migration Manager
 * Handles database migrations like Laravel
 */
class Migration
{
    protected Database $db;
    protected string $migrationsPath;

    public function __construct()
    {
        $this->db = new Database();
        $this->migrationsPath = __DIR__ . '/../database/migrations';
    }

    /**
     * Run all pending migrations
     */
    public function runMigrations()
    {
        if (!is_dir($this->migrationsPath)) {
            mkdir($this->migrationsPath, 0755, true);
        }

        // Créer la table migrations si elle n'existe pas
        $this->createMigrationsTable();

        $migrations = $this->getPendingMigrations();

        if (empty($migrations)) {
            echo "✓ No pending migrations\n";
            return;
        }

        foreach ($migrations as $migration) {
            $this->executeMigration($migration);
        }

        echo "✓ All migrations executed successfully\n";
    }

    /**
     * Rollback the last batch of migrations
     */
    public function rollback()
    {
        // Récupérer les migrations du dernier batch
        $query = "SELECT * FROM migrations WHERE batch = (SELECT MAX(batch) FROM migrations) ORDER BY migration DESC";
        $migrations = $this->db->fetchAll($query);

        if (empty($migrations)) {
            echo "✓ Nothing to rollback\n";
            return;
        }

        foreach ($migrations as $migration) {
            $this->rollbackMigration($migration['migration']);
        }

        echo "✓ Rollback completed\n";
    }

    /**
     * Create migrations table
     */
    protected function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            migration VARCHAR(255) NOT NULL UNIQUE,
            batch INT NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $this->db->getConnection()->exec($sql);
        } catch (\Exception $e) {
            // Table already exists
        }
    }

    /**
     * Get pending migrations
     */
    protected function getPendingMigrations(): array
    {
        $files = glob($this->migrationsPath . '/*.php');
        $executed = $this->getExecutedMigrations();
        $pending = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (!in_array($name, $executed)) {
                $pending[] = $file;
            }
        }

        return $pending;
    }

    /**
     * Get executed migrations
     */
    protected function getExecutedMigrations(): array
    {
        try {
            $query = "SELECT migration FROM migrations";
            $result = $this->db->fetchAll($query);
            return array_column($result, 'migration');
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Execute a single migration
     */
    protected function executeMigration(string $file)
    {
        $name = basename($file, '.php');

        echo "Migrating: $name\n";

        require $file;

        // Get the class name from the file
        $className = $this->getClassName($name);

        if (class_exists($className)) {
            $migration = new $className();
            $migration->up($this->db);

            // Enregistrer la migration
            $batch = $this->getNextBatch();
            $this->db->insert('migrations', [
                'migration' => $name,
                'batch' => $batch
            ]);

            echo "✓ Migrated: $name\n\n";
        }
    }

    /**
     * Rollback a migration
     */
    protected function rollbackMigration(string $name)
    {
        $file = $this->migrationsPath . '/' . $name . '.php';

        if (!file_exists($file)) {
            return;
        }

        echo "Rolling back: $name\n";

        require $file;

        $className = $this->getClassName($name);

        if (class_exists($className)) {
            $migration = new $className();
            $migration->down($this->db);

            $this->db->delete('migrations', ['migration' => $name]);

            echo "✓ Rolled back: $name\n\n";
        }
    }

    /**
     * Convert migration filename to class name
     * Example: 2024_01_01_000000_create_users_table -> CreateUsersTable
     */
    protected function getClassName(string $filename): string
    {
        $parts = explode('_', $filename);
        array_splice($parts, 0, 3); // Remove timestamp parts

        $className = '';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }

        return 'Database\\Migrations\\' . $className;
    }

    /**
     * Get next batch number
     */
    protected function getNextBatch(): int
    {
        try {
            $result = $this->db->fetch("SELECT MAX(batch) as batch FROM migrations");
            return ($result['batch'] ?? 0) + 1;
        } catch (\Exception $e) {
            return 1;
        }
    }
}
