<?php

namespace app;

use PDO;
use PDOException;

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "#132apokalipsy#";
    private $dbname = "annuaire";
    private $charset = "utf8mb4";

    private $conn;

    public function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
