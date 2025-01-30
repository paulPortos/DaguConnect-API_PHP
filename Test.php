<?php

namespace DaguConnect;
// Instantiate the class
require_once 'Includes/config.php';

use DaguConnect\Includes\config;

class Test {
    public function __construct() {
        var_dump("Test is running");
        new config();
    }
}