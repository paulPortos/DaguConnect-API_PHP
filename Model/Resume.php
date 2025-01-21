<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;

class Resume extends BaseModel
{
    protected $table = 'user_resume';


    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function resume($user_id,$title,$description):bool
    {
        $query = "INSERT INTO $this->table 
                    (user_id, title, description,created_at) 
                    VALUES(:user_id, :title, :description, NOW())";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);


        return  $stmt->execute();

    }
}