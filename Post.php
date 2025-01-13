<?php

class Post
{
    private $conn;
    private $table = 'posts';

    public $id;
    public $name;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT
            c.name as cat_name,
            p.id,
            p.created_at
            FROM 
            '.$this->table . ' p 
            LEFT JOIN 
            categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

}