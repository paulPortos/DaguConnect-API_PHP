<?php

namespace DaguConnect\Services;

use DaguConnect\Includes\config;
use InvalidArgumentException;
use PDO;

trait IfDataExists
{
    protected config $db;
    public function exists($value, $column, $table):bool {

        // So that data leaks can be prevented
        $allowedColumns = ['email', 'username', 'id'];
        if (!in_array($column, $allowedColumns)) {
            throw new InvalidArgumentException("Invalid column name.");
        }

        $query = "SELECT COUNT(*) AS count FROM $table WHERE $column = :value";

        $stmt = $this->db->getDB()->prepare($query);

        $stmt->bindParam(':value', $value);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return true if the count is greater than 0
        return $result['count'] > 0;
    }
}