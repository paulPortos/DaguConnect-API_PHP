<?php

namespace core;
class Post
{
    private $conn;
    private string $table = 'posts';

    public $id;
    public $name;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT
            id,
            name,
            created_at
          FROM 
            ' . $this->table . ' 
          ORDER BY created_at DESC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

}