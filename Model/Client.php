<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;

use PDO;
class Client extends BaseModel
{
    protected $table = 'client_booking';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function BookTradesman($user_id, $resume_id,$tradesman_id,$task_type,$task): bool
    {
        // Correct the query to explicitly define column names
        $query = "INSERT INTO $this->table 
                    (user_id, resume_id, tradesman_id,task_type ,task,booking_status,created_at)
                    VALUES (:user_id,:resume_id ,:tradesman_id,:task_type, :task,'Pending',NOW())";

        $stmt = $this->db->prepare($query);

        // Bind parameters to the placeholders
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':resume_id', $resume_id);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->bindParam(':task_type', $task_type);
        $stmt->bindParam(':task', $task);


        return $stmt->execute();
    }


}