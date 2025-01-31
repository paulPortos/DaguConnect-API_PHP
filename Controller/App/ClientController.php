<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Client;
use DaguConnect\Services\GetResumeIdByTradesmanId;
use DaguConnect\Services\ValidatePhoneNumber;


class ClientController extends BaseController
{
    private Client $client;
    private array $Book_type;
    private config $db;

    use GetResumeIdByTradesmanId;
    use ValidatePhoneNumber;

    public function __construct(Client $client)
    {
        $this->Book_type = ['Carpentry','Painting','Welding','Electrician','Plumbing','Masonry','Roofing','AC repair','Mechanics','Cleaning'];
        $this->db = new config();
        $this->client = $client;
    }

    public function BookTradesman($user_id,$tradesman_id,$phone_number,$address,$task_type,$task_description,$booking_date): void
    {
        try{
            if( empty($tradesman_id) ||  empty($phone_number) || empty($address) ||empty($task_type) || empty($task_description) || empty($booking_date) ){
                $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
                return;
            }

            //Check if the job type is valid
            if (!in_array($task_type, $this->Book_type, true)) {
                $this->jsonResponse(['message' => "Invalid Booking type"], 400);
                return;
            }
            //check if it's valid phone_number
            if(!$this->validatePhoneNumber($phone_number)){
                $this->jsonResponse(['message'=>'Invalid phone number']);
                return;
            }
            // get resume ID by tradesman ID and task_type
            $resume_id = $this->getResumeIdByTradesmanId($tradesman_id ,$this->db->getDB());

            // Handle case where tradesman does not exist
            if (!$resume_id) {
                $this->jsonResponse(['message' => "Tradesman with ID $tradesman_id does not exist."], 404);
                return;
            }

            $result = $this->client->BookTradesman($user_id,$resume_id['id'],$tradesman_id,$phone_number,$address,$task_type,$task_description,$booking_date );


            if($result){
                $this->jsonResponse(['message' => 'Booking created successfully.'],201);
            }else{
                $this->jsonResponse(['message' => 'Something went wrong.'],500);
            }

        }catch (\Exception $e){
            $this->jsonResponse(['message' => $e->getMessage()],500);
        }

    }

    //get booking of the clients
    public function GetBookingClient($user_id){
        try{
            $ClientBooking = $this->client->GetBooking($user_id);
            if(!$ClientBooking){
                $this->jsonResponse(['message' => "No bookings found"], 400);
            }

            if($ClientBooking){
                $this->jsonResponse(['message' => 'Booking Successfully retrieve',
                   'client_bookings' => $ClientBooking  ],200);
            }

        }catch (\Exception $e){
            $this->jsonResponse(['message' => $e->getMessage()],500);
        }
    }

    public function UpdateWorkFromTradesman($user_id,$booking_id,$work_status): void{

        //check if the booking belongs to the user and if exists
        if($this->client->ValidateWorkUpdate($user_id,$booking_id)){
            $this->jsonResponse(['message' => 'Booking not found or does not belong to the client',
                'booking_id' => $booking_id,
                'user_id' => $user_id],
                400);
            return;
        }

        // Ensure status is either 'Finished' or 'Failed'
        if (!in_array($work_status, ['Finished', 'Failed'])) {
            $this->jsonResponse(['message' => 'Invalid status provided.'], 400);
            return;
        }
        //update the work_status if the client mark it as finish or not
        $UpdateWorkStatus = $this->client->UpdateWorkStatus($user_id,$booking_id,$work_status);
        if($UpdateWorkStatus){
            $this->jsonResponse(['message' => 'Work status updated successfully.'],200);
        }else {
            $this->jsonResponse(['message' => 'Failed to update work status.'], 400);
        }



    }


}

