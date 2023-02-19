<?php

declare(strict_types=1);

namespace TBuilder\Database;

use PDO;
use PDOException;
use PDOStatement;

/**
 * TBuilder package
 *
 * @package TBuilder\Database
 * @author  Thiago Silva <thiagom.devsec@gmail.com>
 * @version 1.0
 */
final class QueryBuilder
{

    /**
     * Database connection
     *
     * @var PDO
     */
    private PDO $db;

    /**
     * Make Database connection
     *
     * @return PDO
     */
    private function connect(): PDO
    {
        try {
            $this->conn = new PDO("mysql:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName}",
                $this->dbUser, $this->dbPass);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die("ERROR: {$e->getMessage()}");
        }
    }

    /**
     * Init TBuilder with database credentials
     *
     * @param string $dbHost
     * @param int $dbPort
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPass
     */
    public function __construct(
        private string $dbHost,
        private int $dbPort,
        private string $dbName,
        private string $dbUser,
        private string $dbPass
    )
    {
        $this->db = $this->connect($dbHost, $dbPort, $dbName, $dbUser, $dbPass);
    }

    /**
     * Execute querystring
     *
     * @param string $queryString
     * @param array $params
     * @return PDOStatement
     */
    private function executeQuery(string $queryString, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->db->prepare($queryString);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            die("=> Error to execute query {$queryString}\n Message: {$e->getMessage()}");
        }
    }

    /**
     * TBuilder Select
     *
     * @param string $table
     * @param string|null $fields
     * @param string|null $where
     * @param string|null $groupBy
     * @param string|null $orderBy
     * @param string|null $limitBy
     * @return array
     */
    public function select(
        string  $table,
        ?string $fields = null,
        ?string $where = null,
        ?string $groupBy = null,
        ?string $orderBy = null,
        ?string $limitBy = null
    ): array
    {
        $fields = is_null($fields) ? '*' : $fields;
        $where = is_null($where) ? '' : "WHERE {$where}";
        $groupBy = is_null($groupBy) ? '' : "GROUP BY {$groupBy}";
        $orderBy = is_null($orderBy) ? '' : "ORDER BY {$orderBy}";
        $limitBy = is_null($limitBy) ? '' : "LIMIT BY {$limitBy}";

        $queryString = "SELECT {$fields} FROM {$table} {$where} {$groupBy} {$orderBy} {$limitBy}";

        return ($this->executeQuery($queryString)->fetchAll());
    }

    /**
     * TBuilder Insert
     *
     * @param string $table
     * @param array $values
     * @return int
     */
    public function insert(string $table, array $values): int
    {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        $implodeFields = implode(',', $fields);
        $implodeBinds = implode(',', $binds);

        $queryString = "INSERT INTO {$table} ({$implodeFields}) VALUES ({$implodeBinds})";
        $this->executeQuery($queryString, array_values($values));

        return (int)$this->db->lastInsertId();
    }

    /**
     * TBuilder Update
     *
     * @param string $table
     * @param string $where
     * @param array $payload
     * @return bool
     */
    public function update(string $table, string $where, array $payload): bool
    {
        $fields = array_keys($payload);
        $implodeFields = implode('=?,', $fields);

        $queryString = "UPDATE {$table} SET {$implodeFields}=? WHERE {$where}";
        $result = $this->executeQuery($queryString, array_values($payload));

        return $result->rowCount() == '1' ? true : false;
    }

    /**
     * TBuilder Delete
     *
     * @param string $table
     * @param string $where
     * @return bool
     */
    public function delete(string $table, string $where = null): bool
    {
        $where = is_null($where) ? '' : "WHERE {$where}";
        $queryString = "DELETE FROM {$table} {$where};";

        $result = $this->executeQuery($queryString);

        return $result->rowCount() == '1' ? true : false;
    }
}
