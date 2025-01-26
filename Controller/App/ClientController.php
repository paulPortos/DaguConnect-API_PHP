<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Includes\config;
use DaguConnect\Model\Client;
use DaguConnect\Services\GetResumeIdByTradesmanId;


class ClientController extends BaseController
{
    private Client $client;
    private config $db;

    use GetResumeIdByTradesmanId;

    public function __construct(Client $client)
    {
        $this->db = new config();
        $this->client = $client;
    }

    public function BookTradesman($user_id,$tradesman_id,$task_type,$task): void
    {
        try{
            if( empty($tradesman_id) || empty($task_type) || empty($task)){
                $this->jsonResponse(['message' => 'Please fill all the fields.'],400);
                return;
            }

            $resume_id = $this->getResumeIdByTradesmanId($tradesman_id, $this->db->getDB());

            $result = $this->client->BookTradesman($user_id,$resume_id['id'],$tradesman_id,$task_type,$task);

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

