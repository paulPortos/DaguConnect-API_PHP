<?php

namespace DaguConnect\Model;
use DaguConnect\Core\BaseModel;

use PDO;
use PDOException;

class Tradesman extends BaseModel
{
    protected $table = 'client_booking';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    // Get all the bookings from the tradesman
    public function getClientsBooking($tradesman_id, int $pages, int $limit): array
    {
        $offset = ($pages - 1) * $limit;
        try {
            // Check for expired bookings before retrieving (no rate limiting needed)
            $this->checkAllExpiredBookings();

            $count_stmt   = $this->db->prepare("SELECT COUNT(*) as total FROM $this->table WHERE tradesman_id = :tradesman_id ");
            $count_stmt->bindParam(':tradesman_id', $tradesman_id, PDO::PARAM_INT);
            $count_stmt->execute();
            $totalApplicants = (int) $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

            $query = "SELECT * FROM $this->table WHERE tradesman_id = :tradesman_id LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':tradesman_id', $tradesman_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalPages = max(1, ceil($totalApplicants / $limit));

            return [
                'bookings' => $bookings,
                'current_page' => $pages,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            error_log("error: " . $e->getMessage());
            return [];
        }
    }

    // Update the booking_status if it is declined or accepted
    public function UpdateBookStatus($booking_status, $booking_id, $tradesman_id, $cancel_reason): bool
    {
        try {
            // First, check if the booking has expired
            $this->checkAndUpdateExpiredBooking($booking_id);

            // Proceed with the original update logic
            $query = "UPDATE $this->table SET
                  booking_status = :booking_status, 
                  cancel_reason = :cancel_reason, 
                  booking_date_status = NOW()
                  WHERE id = :booking_id AND tradesman_id = :tradesman_id";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_status', $booking_status);
            $stmt->bindParam(':booking_id', $booking_id);
            $stmt->bindParam(':tradesman_id', $tradesman_id);
            $stmt->bindParam(':cancel_reason', $cancel_reason);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the error message and return false
            error_log('Error on updating: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if the booking has expired and update its status if necessary
     */
    public function checkAndUpdateExpiredBooking($booking_id): void
    {
        try {
            // Fetch the booking details
            $query = "SELECT booking_date, booking_status 
                  FROM $this->table 
                  WHERE id = :booking_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id);
            $stmt->execute();
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
                $booking_date = strtotime($booking['booking_date']);
                $current_date = strtotime(date('Y-m-d')); // Current date without time

                // Check if the booking date is in the past and status is Pending
                if ($booking_date < $current_date && $booking['booking_status'] === 'Pending') {
                    $update_query = "UPDATE $this->table SET
                                 booking_status = :booking_status, 
                                 cancel_reason = :cancel_reason, 
                                 booking_date_status = NOW()
                                 WHERE id = :booking_id";

                    $update_stmt = $this->db->prepare($update_query);
                    $new_status = 'Expired';
                    $new_reason = 'Expired Booking';

                    $update_stmt->bindParam(':booking_status', $new_status);
                    $update_stmt->bindParam(':cancel_reason', $new_reason);
                    $update_stmt->bindParam(':booking_id', $booking_id);

                    $update_stmt->execute();
                }
            }
        } catch (PDOException $e) {
            error_log('Error on checking expired booking: ' . $e->getMessage());
        }
    }

    /**
     * Method to check all bookings and update expired ones
     */
    public function checkAllExpiredBookings(): void
    {
        try {
            // Select all bookings that are Pending
            $query = "SELECT id, booking_date, booking_status 
                  FROM $this->table 
                  WHERE booking_status = 'Pending'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $current_date = strtotime(date('Y-m-d')); // Current date without time

            foreach ($bookings as $booking) {
                $booking_date = strtotime($booking['booking_date']);
                $booking_id = $booking['id'];

                // If the booking date is in the past, cancel it
                if ($booking_date < $current_date) {
                    $update_query = "UPDATE $this->table SET
                                 booking_status = :booking_status, 
                                 cancel_reason = :cancel_reason, 
                                 booking_date_status = NOW()
                                 WHERE id = :booking_id";

                    $update_stmt = $this->db->prepare($update_query);
                    $new_status = 'Cancelled';
                    $new_reason = 'Expired Booking';

                    $update_stmt->bindParam(':booking_status', $new_status);
                    $update_stmt->bindParam(':cancel_reason', $new_reason);
                    $update_stmt->bindParam(':booking_id', $booking_id);

                    $update_stmt->execute();
                    error_log("Updated booking ID $booking_id to Cancelled due to expiration");
                }
            }
        } catch (PDOException $e) {
            error_log('Error on checking all expired bookings: ' . $e->getMessage());
        }
    }

    // Validate if the booking exists or if the booking belongs to the tradesman
    public function ValidateBookingUpdate($tradesman_id, $booking_id): bool
    {
        try {
            $query = "SELECT COUNT(*) FROM $this->table WHERE id = :booking_id AND tradesman_id = :tradesman_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->bindParam(':tradesman_id', $tradesman_id, PDO::PARAM_INT);
            $stmt->execute();

            // Return true if the booking exists, false otherwise
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Error on updating: ' . $e->getMessage());
            return false;
        }
    }
}