<?php

namespace Database\Migrations;

use Core\Database;

/**
 * Base Migration Class
 * Extend this to create your migrations
 */
abstract class Migration
{
    /**
     * Run the migration
     */
    abstract public function up(Database $db);

    /**
     * Reverse the migration
     */
    abstract public function down(Database $db);
}
