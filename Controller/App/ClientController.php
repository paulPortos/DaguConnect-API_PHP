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
    private config $db;

    use GetResumeIdByTradesmanId;
    use ValidatePhoneNumber;

    public function __construct(Client $client)
    {
        $this->db = new config();
        $this->client = $client;
    }

    public function BookTradesman($user_id,$tradesman_id,$phone_number,$address,$task_type,$task): void
    {
        try{
            if( empty($tradesman_id) ||  empty($phone_number) || empty($address) ||empty($task_type) || empty($task)){
                $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
                return;
            }

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

            $result = $this->client->BookTradesman($user_id,$resume_id['id'],$tradesman_id,$phone_number,$address,$task_type,$task);


            if($result){
                $this->jsonResponse(['message' => 'Booking created successfully.'],201);
            }else{
                $this->jsonResponse(['message' => 'Something went wrong.'],500);
            }

        }catch (\Exception $e){
            $this->jsonResponse(['message' => $e->getMessage()],500);
        }

    }
}

