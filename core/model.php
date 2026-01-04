<?php

namespace Core;

class Model
{
    protected $database;

    public function __construct()
    {
        $this->database = new Database(
            DB_HOST,
            DB_NAME,
            DB_USER,
            DB_PASS
        );
    }

    /**
     * Retourne l'instance Database
     */
    protected function db(): Database
    {
        return $this->database;
    }
}
