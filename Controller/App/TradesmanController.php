<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Tradesman;


class TradesmanController extends BaseController
{
    private Tradesman $tradesman;


    public function __construct(Tradesman $tradesman)
    {

        $this->tradesman = $tradesman;
    }

    public function GetBookingFromClient($userId):void{
        try {
            //fetch the booking base from the tradesman_id
            $booking = $this->tradesman->getClientsBooking($userId);

            //check if there's any booking
            if(!$booking){
                $this->jsonResponse(['message' => 'No booking found.'],400);
                return;
            }

            if(!$userId){
                $this->jsonResponse(['message' => 'This booking is not yours'],400);
                return;
            }
            // Return the booking details
            $this->jsonResponse(['message' => 'Booking retrieved successfully.', 'data' => $booking], 200);

        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
    public function UpdateBookingFromClient($booking_status,$booking_id,$tradesman_id):void{

        //ensure that all the required fields are filled up
        if(empty($booking_status)){
            $this->jsonResponse(['message' => 'Booking status are required.'],400);
            return;
        }
        // Ensure status is either 'Accepted' or 'Rejected'
        if (!in_array($booking_status, ['Accepted', 'Rejected'])) {
            $this->jsonResponse(['message' => 'Invalid status provided.'], 400);
            return;
        }
        $work_status = $booking_status == 'Accepted' ? 'Active' : null;

        if(!$this->tradesman->ValidateBookingUpdate($booking_id,$tradesman_id)){
            $this->jsonResponse([
                'message' => 'Booking not found or does not belong to the tradesman.',
                'booking_id' => $booking_id,
                'tradesman_id' => $tradesman_id
            ], 400);
            return;
        }
        //update the booking_status
        $updatebooking = $this->tradesman->UpdateBookStatus($booking_status, $work_status, $booking_id, $tradesman_id);

        //check if the booking is accepted or declined
        if($updatebooking){
            $message = $booking_status == 'Accepted' ? 'Booking accepted successfully' : 'Booking rejected ';
            $this->jsonResponse(['message' => $message,
                                'booking_id' =>   $booking_id,
                                'tradesman_id' =>  $tradesman_id ],200);
        }else {
            $this->jsonResponse(['message' => 'Failed to update the booking status.'], 500);
        }

    }
}