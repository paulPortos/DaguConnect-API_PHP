<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Client;


class ClientController extends BaseController
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function BookTradesman($user_id,$resume_id,$task_type,$task,$booking_status): void
    {
        try{
            if( empty($resume_id) || empty($task_type) || empty($task) || empty($booking_status)){
                $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
                return;
            }

            $result = $this->client->BookTradesman($user_id,$resume_id,$task_type,$task,$booking_status);

            if($result){
                $this->jsonResponse(['message' => 'Booking created successfully.'],200);
            }else{
                $this->jsonResponse(['message' => 'Something went wrong.'],500);
            }

        }catch (\Exception $e){
            $this->jsonResponse(['message' => $e->getMessage()],500);
        }



    }
}

