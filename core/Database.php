<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $dbname;
    private $user;
    private $pass;
    private $pdo;

    public function __construct($host = 'localhost', $dbname = 'bondo', $user = 'root', $pass = '')
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    /**
     * Préparer et exécuter une requête
     */
    public function query(string $sql, array $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Erreur de requête: " . $e->getMessage());
        }
    }

    /**
     * Récupérer tous les résultats
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Récupérer une seule ligne
     */
    public function fetch(string $sql, array $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Insérer des données
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
        $this->query($sql, array_values($data));

        return $this->pdo->lastInsertId();
    }

    /**
     * Mettre à jour des données
     */
    public function update(string $table, array $data, array $where): bool
    {
        $set = implode(', ', array_map(fn($key) => "{$key} = ?", array_keys($data)));
        $condition = implode(' AND ', array_map(fn($key) => "{$key} = ?", array_keys($where)));

        $sql = "UPDATE {$table} SET {$set} WHERE {$condition}";
        $params = array_merge(array_values($data), array_values($where));

        return $this->query($sql, $params)->rowCount() > 0;
    }

    /**
     * Supprimer des données
     */
    public function delete(string $table, array $where): bool
    {
        $condition = implode(' AND ', array_map(fn($key) => "{$key} = ?", array_keys($where)));
        $sql = "DELETE FROM {$table} WHERE {$condition}";

        return $this->query($sql, array_values($where))->rowCount() > 0;
    }

    /**
     * Compter les lignes
     */
    public function count(string $table, array $where = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        $params = [];

        if (!empty($where)) {
            $condition = implode(' AND ', array_map(fn($key) => "{$key} = ?", array_keys($where)));
            $sql .= " WHERE {$condition}";
            $params = array_values($where);
        }

        $result = $this->fetch($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Obtenir la connexion PDO
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
