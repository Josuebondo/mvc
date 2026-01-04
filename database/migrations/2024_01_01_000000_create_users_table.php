<?php

namespace Database\Migrations;

use Core\Database;

/**
 * Create users table
 */
class CreateUsersTable extends Migration
{
    public function up(Database $db)
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->getConnection()->exec($sql);
    }

    public function down(Database $db)
    {
        $db->getConnection()->exec("DROP TABLE IF EXISTS users");
    }
}
