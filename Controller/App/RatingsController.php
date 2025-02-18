<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Client;
use DaguConnect\Model\Rating;
use DaguConnect\Model\User;

class RatingsController extends BaseController
{
    private Rating $rating;
    private User $User;

    private Client $client;
    public function __construct(rating $rating_model, user $user_model ,client $client){
        $this->rating = $rating_model;
        $this->User = $user_model;
        $this->client = $client;
    }

    public function rateTradesman($client_id,$booking_id,$rating,$message,){

        $Client_detail = $this->User->getClientDetails($client_id);

        $client_name = $Client_detail['fullname'];

        $completedStatus = $this->client->CheckCompletedBookings($client_id,$booking_id);

        if(!$completedStatus){
            $this->jsonResponse([
                'message' => "this booking does not exist or isn't yet Completed",
            ],400);
            return;
        }

        $ExistingRating = $this->rating->ExistingRating($client_id,$booking_id);
        if($ExistingRating){
            $this->jsonResponse(['message'=>'Already Rated This booking'],400);
            return;
        }
        $ratingtradesman = $this->rating->RateTradesman($client_id,$booking_id,$rating,$message,$client_name);

        if(!$ratingtradesman){
            $this->jsonResponse([
                'message' => "Rating Tradesman cannot be found",
                ],400
            );
        }else{
            $this->jsonResponse([
                'message' => "Rating Successful",
            ]);
        }
    }



}