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

    public function ReportTradesman($reported_by_id,$reported_id,$report_reason,$report_details,$reporters_email,$reporters_profile,$tradesman_fullname,$client_fullname,$fullReportUrl):bool{
        $query = "INSERT INTO $this->table(reported_by_id, reported_id,report_reason,report_details,reporters_email,reporters_profile,reported_by,reported,reporter,report_attachment,report_status,reported_date)
                    VALUES(:reported_by_id,:reported_id,:reason,:details,:reporters_email,:reporters_profile,:client_fullname,:tradesman_fullname,'Client',:report_attachment,'Pending',NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':reported_by_id', $reported_by_id);
        $stmt->bindParam(':reported_id', $reported_id);
        $stmt->bindParam(':reason', $report_reason);
        $stmt->bindParam(':details', $report_details);
        $stmt->bindParam(':reporters_email', $reporters_email);
        $stmt->bindParam(':reporters_profile', $reporters_profile);
        $stmt->bindParam(':tradesman_fullname', $tradesman_fullname);
        $stmt->bindParam(':client_fullname', $client_fullname);
        $stmt->bindParam(':report_attachment', $fullReportUrl);

        return $stmt->execute();
    }

    public function ReportClient($reported_by_id,$reported_id,$report_reason,$report_details,$reporters_email,$reporters_profile,$tradesman_fullname,$client_fullname,$fullReportUrl){
        $query = "INSERT INTO $this->table(reported_by_id, reported_id,report_reason,report_details,reporters_email,reporters_profile,reported_by,reported,reporter,report_attachment,report_status,reported_date)
                    VALUES(:reported_by_id,:reported_id,:reason,:details,:reporters_email,:reporters_profile,:tradesman_fullname,:client_fullname,'Tradesman',:report_attachment,'Pending',NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':reported_by_id', $reported_by_id);
        $stmt->bindParam(':reported_id', $reported_id);
        $stmt->bindParam(':reason', $report_reason);
        $stmt->bindParam(':details', $report_details);
        $stmt->bindParam(':reporters_email', $reporters_email);
        $stmt->bindParam(':reporters_profile', $reporters_profile);
        $stmt->bindParam(':tradesman_fullname', $tradesman_fullname);
        $stmt->bindParam(':client_fullname', $client_fullname);
        $stmt->bindParam(':report_attachment', $fullReportUrl);

        return $stmt->execute();
    }

    public function ExistingReport($reported_by_id,$reported_id){

        $query = "SELECT COUNT(*) FROM $this->table WHERE
                    reported_by_id = :reported_by_id AND reported_id = :reported_id
                    AND report_status = 'Pending'
                    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':reported_id', $reported_id);
        $stmt->bindParam(':reported_by_id', $reported_by_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function updateTradesmanProfileInReport($user_id, $profile_pic_url): void
    {
        try {
            $query = "UPDATE $this->table 
                  SET tradesman_profile = :profile_pic_url 
                  WHERE reported_by_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':profile_pic_url', $profile_pic_url);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating tradesman profile in report: " . $e->getMessage());
        }
    }



}