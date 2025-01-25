<?php
require_once __DIR__ . '/migration.php'; // Include the migration class
require_once __DIR__ . '/Includes/config.php'; // Include the config class

use DaguConnect\migration; // Use the correct namespace and class name
use DaguConnect\Includes\config; // Use the correct namespace for config

$config = new config();
$pdo = $config->getDB();

// Create a new instance of the migrate class
$migrations = new migration($pdo);