<?php

namespace Core;

use PDO;

class BaseModel
{
    protected PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
}