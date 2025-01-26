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

    public function GetBookingFromClient($userId):void{
        try {
            //fetch the booking base from the tradesman_id
            $booking = $this->tradesman->getClientsBooking($userId);

            //check if there's any booking
            if(!$booking){
                $this->jsonResponse(['message' => 'No booking found.'],400);
                return;
            }

            // Return the booking details
            $this->jsonResponse(['message' => 'Booking retrieved successfully.', 'data' => $booking], 200);

        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}