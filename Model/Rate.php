<?php

namespace DaguConnect\Model;

use DaguConnect\Core\BaseModel;
use PDO;

class Rate extends BaseModel
{
    protected $table = 'tradesman_rate';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }


}