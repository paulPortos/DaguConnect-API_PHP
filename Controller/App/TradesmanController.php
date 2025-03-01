<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Tradesman;


class TradesmanController extends BaseController
{
    private Tradesman $tradesman;


    public function __construct(Tradesman $tradesman)
    {

        $this->tradesman = $tradesman;
    }

    //get booking from the clients of the tradesman
    public function GetBookingFromClient($userId,$pages,$limit):void{
        try {
            //fetch the booking base from the tradesman_id
            $booking = $this->tradesman->getClientsBooking($userId,$pages,$limit);

            //check if there's any booking
            if(!$booking){
                $this->jsonResponse(['message' => 'No booking found.'],400);
                return;
            }

            // Return the booking details
            $this->jsonResponse( $booking);

        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    //update the booking if is accepted or rejected by the tradesman
    public function UpdateBookingFromClient($tradesman_id,$booking_id,$booking_status):void{

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
        $booking_update = $booking_status == 'Accepted' ? 'Active' : null;

        //check if the booking exist or the booking belongs to the tradesman
        if(!$this->tradesman->ValidateBookingUpdate($tradesman_id,$booking_id)){
            $this->jsonResponse([
                'message' => 'Booking not found or does not belong to the tradesman.',
                'booking_id' => $booking_id,
                'tradesman_id' => $tradesman_id
            ], 400);
            return;
        }
        //update the booking_status
        $updatebooking = $this->tradesman->UpdateBookStatus($booking_update, $booking_id, $tradesman_id);

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