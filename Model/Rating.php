<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;

class Rating extends BaseModel
{
    protected $table = 'ratings';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function RateTradesman($client_id, $tradesman_id, $rating, $message, $client_name, $profile_picture, $tradesman_fullname): bool {
        try {
            // Start a transaction
            $this->db->beginTransaction();

            // Insert new rating into the ratings table
            $query = "INSERT INTO ratings (client_id, tradesman_id, tradesman_fullname, client_profile, ratings, message, client_name, rated_at) 
                  VALUES (:client_id, :tradesman_id, :tradesman_fullname, :profile_picture, :rating, :message, :client_name, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':tradesman_id', $tradesman_id);
            $stmt->bindParam(':tradesman_fullname', $tradesman_fullname);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':client_name', $client_name);
            $stmt->bindParam(':profile_picture', $profile_picture);
            $stmt->execute();

            // Calculate the new average rating
            $averageQuery = "SELECT AVG(ratings) AS avg_rating FROM ratings WHERE tradesman_id = :rating_tradesman_id";
            $averageStmt = $this->db->prepare($averageQuery);
            $averageStmt->bindParam(':rating_tradesman_id', $tradesman_id);
            $averageStmt->execute();
            $averageResult = $averageStmt->fetch(PDO::FETCH_ASSOC);
            $averageRating = $averageResult['avg_rating'] ?? 0;

            // Update the total rating in tradesman_resume
            $updateQuery = "UPDATE tradesman_resume 
                        SET ratings = :average_rating
                        WHERE user_id = :resume_tradesman_id";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':average_rating', $averageRating);
            $updateStmt->bindParam(':resume_tradesman_id', $tradesman_id);
            $updateStmt->execute();

            // Update the ratings in client_booking for all bookings related to this tradesman
            $updateBookingQuery = "UPDATE client_booking 
                               SET ratings = :average_rating 
                               WHERE tradesman_id = :booking_tradesman_id";
            $updateBookingStmt = $this->db->prepare($updateBookingQuery);
            $updateBookingStmt->bindParam(':average_rating', $averageRating);
            $updateBookingStmt->bindParam(':booking_tradesman_id', $tradesman_id);
            $updateBookingStmt->execute();

            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $this->db->rollBack();
            return false;
        }
    }




    public function ExistingRating($client_id,$trademan_id){
        $query = "SELECT COUNT(*) FROM  $this->table  WHERE 
                client_id = :client_id AND tradesman_id = :trademan_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':trademan_id', $trademan_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function viewratings($tradesman_id){
        $query = "SELECT * FROM $this->table WHERE
                        tradesman_id = :tradesman_id ORDER BY rated_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tradesman_id', $tradesman_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}