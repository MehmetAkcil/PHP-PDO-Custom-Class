<?php

class Database
{
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private PDO $connection;

    public function __construct($host, $dbname, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    private function connect(): void
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return;
        } catch (PDOException $e) {
            return;
        }
    }

    public function query($sql, $params = array())
    {
        $this->connect();
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function findAll($table, $limit = false)
    {
        $sql = "SELECT * FROM $table" . ($limit !== false ? " LIMIT $limit" : "");
        return $this->query($sql);
    }

    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        return $this->query($sql, $data);
    }

    public function update($table, $columnPrimary, $id, $data)
    {
        $setColumns = '';
        foreach ($data as $column => $value) {
            $setColumns .= $column . ' = :' . $column . ', ';
        }
        $setColumns = rtrim($setColumns, ', ');

        $sql = "UPDATE {$table} SET {$setColumns} WHERE {$columnPrimary} = :id";
        $data['id'] = $id;
        return $this->query($sql, $data);
    }

    public function find($table, $column, $value)
    {
        $sql = "SELECT * FROM {$table} WHERE {$column} = :value";
        $params = array(':value' => $value);
        $result = $this->query($sql, $params);
        if ($result !== false) {
            return $result[0] ?? null;
        }
        return null;
    }

    public function delete($table, $column, $value)
    {
        $sql = "DELETE FROM {$table} WHERE {$column} = :value";
        $params = array(':value' => $value);
        return $this->query($sql, $params);
    }

    public function count($table) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        $result = $this->query($sql);
        if ($result !== false) {
            return $result[0]['count'] ?? 0;
        }
        return 0;
    }

    public function beginTransaction() {
        $this->connection->beginTransaction();
    }

    public function commit() {
        $this->connection->commit();
    }

    public function rollback() {
        $this->connection->rollback();
    }

    public function getLastInsertId() {
        return $this->connection->lastInsertId();
    }

    public function findBy($table, $conditions = array()) {
        $where = '';
        $params = array();
        foreach ($conditions as $column => $value) {
            $where .= "{$column} = :{$column} AND ";
            $params[":{$column}"] = $value;
        }
        $where = rtrim($where, ' AND ');

        $sql = "SELECT * FROM {$table} WHERE {$where}";
        return $this->query($sql, $params);
    }

    public function orderBy($table, $column, $direction = 'ASC') {
        $sql = "SELECT * FROM {$table} ORDER BY {$column} {$direction}";
        return $this->query($sql);
    }

    public function limit($table, $limit) {
        $sql = "SELECT * FROM {$table} LIMIT {$limit}";
        return $this->query($sql);
    }

    public function join($table1, $table2, $onCondition) {
        $sql = "SELECT * FROM {$table1} JOIN {$table2} ON {$onCondition}";
        return $this->query($sql);
    }

    public function sum($table, $column) {
        $sql = "SELECT SUM({$column}) as total FROM {$table}";
        $result = $this->query($sql);
        if ($result !== false) {
            return $result[0]['total'] ?? 0;
        }
        return 0;
    }

    public function paginate($table, $perPage, $currentPage = 1) {
        $offset = ($currentPage - 1) * $perPage;
        $sql = "SELECT * FROM {$table} LIMIT {$perPage} OFFSET {$offset}";
        return $this->query($sql);
    }

    public function groupBy($table, $column) {
        $sql = "SELECT * FROM {$table} GROUP BY {$column}";
        return $this->query($sql);
    }

    public function bulkInsert($table, $data) {
        $columns = implode(', ', array_keys($data[0]));
        $placeholders = '';
        $params = array();
        foreach ($data as $index => $row) {
            $rowPlaceholders = array();
            foreach ($row as $column => $value) {
                $param = ":{$column}_{$index}";
                $rowPlaceholders[] = $param;
                $params[$param] = $value;
            }
            $placeholders .= '(' . implode(', ', $rowPlaceholders) . '), ';
        }
        $placeholders = rtrim($placeholders, ', ');

        $sql = "INSERT INTO {$table} ({$columns}) VALUES {$placeholders}";
        return $this->query($sql, $params);
    }


}
