<?php

namespace Model;


use Core\BaseModel;
use PDO;

class User extends BaseModel
{

    protected $table = 'users';

    public function readAll(): array {
        $query = 'SELECT * FROM $this->table';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}