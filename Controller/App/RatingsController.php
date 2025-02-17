<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\Model\Rating;

class RatingsController extends BaseController
{
    private Rating $rating;
    public function __construct(rating $rating_model  ){
        $this->rating = $rating_model;

    }
}