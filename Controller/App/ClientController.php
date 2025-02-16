<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Client;
use DaguConnect\Model\Resume;

use DaguConnect\Model\User;
use DaguConnect\Services\ValidatePhoneNumber;


class ClientController extends BaseController
{
    private Client $client;
    private Resume $resume;

    private User $user;
    private array $Book_type;
    private config $db;

    use ValidatePhoneNumber;

    public function __construct(Client $client,Resume $resume_model, User $user_model)
    {
        $this->Book_type = ['Carpentry','Painting','Welding','Electrician','Plumbing','Masonry','Roofing','ACRepair','Mechanics','Cleaning'];
        $this->db = new config();
        $this->client = $client;
        $this->resume = $resume_model;
        $this->user = $user_model;
    }

    public function BookTradesman($user_id,$tradesman_id,$phone_number,$address,$task_type,$task_description,$booking_date): void
    {
        try{
            if( empty($phone_number) || empty($address) ||empty($task_type) || empty($task_description) || empty($booking_date) ){
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
            // Check if user already has a pending or active booking with this tradesman
            if ($this->client->CheckExistingBooking($user_id, $tradesman_id)) {
                $this->jsonResponse(['message' => 'You are already booked with this tradesman.'], 400);
                return;
            }

            // get resume ID by tradesman ID and task_type
            $resume_id = $this->resume->getResumeIdByTradesmanId($tradesman_id);

            //gets the values from the resume by resume_id
            $tradesmanDetails = $this->resume->getTradesmanDetails($resume_id['id']);

            // Get client details from users table
            $clientDetails = $this->user->getClientDetails($user_id);

            //gets the tradesman_fullaname
            $tradesman_fullname = $tradesmanDetails['tradesman_full_name'];

            //gets the tredesman_profile
            $tradesman_profile = $tradesmanDetails['profile_pic'];

            //gets the work_fee
            $work_fee = $tradesmanDetails['work_fee'];


            //gets the clients_fullname
            $clients_fullname = $clientDetails['fullname'];





            // Handle case where tradesman does not exist
            if (!$resume_id) {
                $this->jsonResponse(['message' => "Tradesman with ID $tradesman_id does not exist."], 404);
                return;
            }

            $result = $this->client->BookTradesman($user_id,$resume_id['id'],$tradesman_id,$phone_number,$tradesman_fullname,$tradesman_profile,$work_fee,$clients_fullname,$address,$task_type,$task_description,$booking_date );


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
                $this->jsonResponse($ClientBooking ,200);
            }

        }catch (\Exception $e){
            $this->jsonResponse(['message' => $e->getMessage()],500);
        }
    }

    public function viewClientBooking($resumeId){
        try{
            $viewbooking = $this->client->ViewBooking($resumeId);
            if(!$viewbooking){
                $this->jsonResponse(['message' => "No booking found"], 400);
            }
            $this->jsonResponse($viewbooking);

        }catch (\Exception $e){
            $this->jsonResponse(['message' => $e->getMessage()],500);
        }
    }

    public function UpdateWorkFromTradesman($user_id,$booking_id,$booking_status): void{

        //check if the booking belongs to the user and if exists
        if(!$this->client->ValidateWorkUpdate($user_id,$booking_id)){
            $this->jsonResponse(['message' => 'Booking not found or does not belong to the client',
                'booking_id' => $booking_id,
                'user_id' => $user_id],
                400);
            return;
        }

        // Ensure status is either 'Finished' or 'Failed'
        if (!in_array($booking_status, ['Completed', 'Failed'])) {
            $this->jsonResponse(['message' => 'Invalid status provided.'], 400);
            return;
        }
        //update the work_status if the client mark it as finish or not
        $UpdateWorkStatus = $this->client->UpdateWorkStatus($user_id,$booking_id,$booking_status);
        if($UpdateWorkStatus){
            $this->jsonResponse(['message' => 'Work status updated successfully.'],200);
        }else {
            $this->jsonResponse(['message' => 'Failed to update work status.'], 400);
        }



    }


}

