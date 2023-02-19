<?php

declare(strict_types=1);

namespace TBuilder\Database;

use PDO;
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

    public function __construct()
    {
        $this->db = (new Database())->open();
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
     * TBuilder select
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
     * TBuilder insert
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
}

