<?php

namespace Core;

use Core\Database;

abstract class Model extends Database
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve all records from the table.
     *
     * @return array The results as an associative array.
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql);
    }

    /**
     * Find a record by its ID.
     *
     * @param int $id The ID of the record.
     * @return array|null The record as an associative array, or null if not found.
     */
    public function find(int $id)
    {
        return $this->findBy('id', $id);
    }

    /**
     * Find a record by a specific column.
     *
     * @param string $column The column to search by.
     * @param mixed $value The value to search for.
     * @return array|null The record as an associative array, or null if not found.
     */
    public function findBy(string $column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        $result = $this->query($sql, ['value' => $value]);
        return $result ? $result[0] : null;
    }

    /**
     * Save a new record to the database.
     *
     * @param array $data The data to insert.
     * @return bool True on success, false on failure.
     */
    public function save(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($col) => ":$col", array_keys($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->query($sql, $data);
    }

    /**
     * Update a record by its ID.
     *
     * @param int $id The ID of the record to update.
     * @param array $data The data to update.
     * @return bool True on success, false on failure.
     */
    public function update(int $id, array $data)
    {
        unset($data['id']);

        $columns = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $columns WHERE id = :id";

        $data['id'] = $id;
        return $this->query($sql, $data);
    }

    /**
     * Delete a record by its ID.
     *
     * @param int $id The ID of the record.
     * @return bool True on success, false on failure.
     */
    public function delete(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->query($sql, ['id' => $id]);
    }
}
