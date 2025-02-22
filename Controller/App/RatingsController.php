<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Client;
use DaguConnect\Model\Client_Profile;
use DaguConnect\Model\Rating;
use DaguConnect\Model\Resume;
use DaguConnect\Model\User;

class RatingsController extends BaseController
{
    private Rating $rating;
    private Client_Profile $client_Profile;

    private Resume $resume;

    private Client $client;
    public function __construct(rating $rating_model, Client_Profile $Client_Profile_model ,client $client,Resume $resume){
        $this->rating = $rating_model;
        $this->client_Profile = $Client_Profile_model;
        $this->client = $client;
        $this->resume = $resume;
    }

    public function rateTradesman($client_id,$tradesman_id,$rating,$message,){

        //gets client details
        $Client_detail = $this->client_Profile->getClientDetails($client_id);

        //gets the clients fullname
        $client_name = $Client_detail['full_name'];

        //gets the client profile picture
        $client_profile = $Client_detail['profile_picture'];

        // get resume ID by tradesman ID and task_type


        // get resume ID by tradesman ID
        $tradesmanDetails = $this->resume->getTradesmanDetails($tradesman_id);

        //gets the tradesman_fullaname
        $tradesman_fullname = $tradesmanDetails['tradesman_full_name'];

        $completedStatus = $this->client->CheckCompletedBookings($client_id,$tradesman_id);

        if(!$completedStatus){
            $this->jsonResponse([
                'message' => "this booking does not exist or isn't yet Completed",
            ],400);
            return;
        }


        $ExistingRating = $this->rating->ExistingRating($client_id,$tradesman_id);
        if($ExistingRating){
            $this->jsonResponse(['message'=>'Already Rated This booking'],400);
            return;
        }


        $ratingtradesman = $this->rating->RateTradesman($client_id,$tradesman_id,$rating,$message,$client_name,$client_profile,$tradesman_fullname);

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