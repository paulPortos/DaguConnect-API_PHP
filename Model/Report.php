<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;
class Report extends BaseModel
{
    protected $table = 'reports';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function ReportTradesman($tradesman_id,$client_id,$report_reason,$report_details,$tradesman_email,$tradesman_profile,$tradesman_fullname,$client_fullname,$fullReportUrl):bool{
        $query = "INSERT INTO $this->table(tradesman_id, client_id,report_reason,report_details,tradesman_email,tradesman_profile,tradesman_fullname,client_fullname,report_attachment,report_status,reported_date)
                    VALUES(:tradesman_id,:client_id,:reason,:details,:tradesman_email,:tradesman_profile,:tradesman_fullname,:client_fullname,:report_attachment,'Pending',NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':reason', $report_reason);
        $stmt->bindParam(':details', $report_details);
        $stmt->bindParam(':tradesman_email', $tradesman_email);
        $stmt->bindParam(':tradesman_profile', $tradesman_profile);
        $stmt->bindParam(':tradesman_fullname', $tradesman_fullname);
        $stmt->bindParam(':client_fullname', $client_fullname);
        $stmt->bindParam(':report_attachment', $fullReportUrl);

        return $stmt->execute();
    }

    public function ExistingReport($tradesman_id,$client_id){

        $query = "SELECT COUNT(*) FROM $this->table WHERE
                   tradesman_id = :tradesman_id AND client_id = :client_id
                   AND report_status = 'Pending'";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function updateTradesmanProfileInReport($user_id, $profile_pic_url): void
    {
        try {
            $query = "UPDATE $this->table 
                  SET tradesman_profile = :profile_pic_url 
                  WHERE tradesman_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':profile_pic_url', $profile_pic_url);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating tradesman profile in report: " . $e->getMessage());
        }
    }

}