<?php

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    public function getAll()
    {
        return $this->db()->fetchAll("SELECT * FROM {$this->table}");
    }

    public function getById($id)
    {
        return $this->db()->fetch("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function create($data)
    {
        return $this->db()->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db()->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db()->delete($this->table, ['id' => $id]);
    }
}