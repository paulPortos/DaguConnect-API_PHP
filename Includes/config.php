<?php

namespace DaguConnect\Includes;

use DaguConnect\Services\Env;
use PDO;
use PDOException;

require_once __DIR__ . '/../Services/Env.php';

class config {

    public $db;

    public function __construct()
    {
        new Env();

        $db_host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];
        $db_username = $_ENV['DB_USERNAME'];
        $db_password = $_ENV['DB_PASSWORD'];
        try {
            $this->db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_username, $db_password);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            define('APP_NAME', $_ENV['APP_NAME']);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getDB():PDO {
        return $this->db;
    }
}
