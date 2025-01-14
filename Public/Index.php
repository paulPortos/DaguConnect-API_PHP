<?php

require_once '../vendor/autoload.php'; // Composer autoloader
require_once '../initialize.php'; // Initialize class

use DaguConnect\initialize;
use Routes\Api;

// Initialize the application
new initialize();

// Create an instance of the API class and handle the request
new Api();