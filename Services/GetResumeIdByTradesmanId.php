<?php

namespace DaguConnect\Services;

use PDO;
trait GetResumeIdByTradesmanId
{

    protected $table = 'tradesman_resume';
    public function getResumeIdByTradesmanId($tradesman_id, PDO $db): ?array
    {
        $query = "SELECT *FROM $this->table WHERE user_id = :tradesman_id ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}