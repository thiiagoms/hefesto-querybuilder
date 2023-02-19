<?php

declare(strict_types=1);

namespace TBuilder\Database;

use PDO;
use PDOException;

/**
 * Database connection packages
 *
 * @package TBuilder\Database
 * @author  Thiago Silva <thiagom.devsec@gmail.com>
 * @version 1.0
 */
final class Database
{

    /**
     * Database Host
     *
     * @var string
     */
    private string $dbHost;

    /**
     * Database Port
     *
     * @var int
     */
    private int $dbPort;

    /**
     * Database name
     *
     * @var string
     */
    private string $dbName;

    /**
     * Database User
     *
     * @var string
     */
    private string $dbUser;

    /**
     * Database password
     *
     * @var string
     */
    private string $dbPass;

    /**
     * Database connection
     *
     * @var PDO
     */
    private PDO $conn;

    /**
     * Init database values
     *
     * @return void
     */
    public function __construct()
    {
        $this->dbHost = $_SERVER['DATABASE_HOST'];
        $this->dbPort = (int) $_SERVER['DATABASE_PORT'];
        $this->dbName = $_SERVER['DATABASE_NAME'];
        $this->dbUser = $_SERVER['DATABASE_USER'];
        $this->dbPass = $_SERVER['DATABASE_PASS'];
    }

    /**
     * Open database connection
     *
     * @return PDO
     */
    public function open(): PDO
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
}