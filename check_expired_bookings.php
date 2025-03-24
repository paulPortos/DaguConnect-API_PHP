6<?php

// Include necessary files
require_once __DIR__ . '/Core/BaseModel.php';
require_once __DIR__ . '/Model/Tradesman.php';
require_once __DIR__ . '/Includes/config.php';
require_once __DIR__ . '/Services/Env.php'; // Ensure Env class is included

use DaguConnect\Model\Tradesman;
use DaguConnect\Includes\config;

// Debug: Confirm script starts
echo "Starting check_expired_bookings.php at " . date('Y-m-d H:i:s') . "\n";
error_log("Starting check_expired_bookings.php at " . date('Y-m-d H:i:s'));

// Initialize the database connection using the config class
try {
    echo "Initializing database connection...\n";
    $config = new config();
    $connection = $config->getDB();
    if ($connection === null) {
        throw new Exception("Database connection is null");
    }
    $tradesman = new Tradesman($connection);
    echo "Database connection successful.\n";
} catch (Exception $e) {
    $errorMsg = "Failed to initialize database or Tradesman: " . $e->getMessage();
    echo $errorMsg . "\n";
    error_log($errorMsg);
    exit(1);
}

// Function to check if it's the end of the day
function isEndOfDay(): bool
{
    $currentHour = (int) date('H'); // Hour in 24-hour format (0-23)
    $currentMinute = (int) date('i'); // Minutes (0-59)

    // For debugging: Trigger immediately
    echo "Current time: $currentHour:$currentMinute\n";
    return true; // Temporarily set to true to test immediately
    // Original condition: return $currentHour === 23 && $currentMinute === 59;
}

// Main loop
while (true) {
    try {
        // Check if it's the end of the day
        if (isEndOfDay()) {
            echo "Running checkAllExpiredBookings at " . date('Y-m-d H:i:s') . "\n";
            error_log("Running checkAllExpiredBookings at " . date('Y-m-d H:i:s'));

            // Call the method to check and update expired bookings
            $tradesman->checkAllExpiredBookings();

            echo "Finished checking expired bookings at " . date('Y-m-d H:i:s') . "\n";
            error_log("Finished checking expired bookings at " . date('Y-m-d H:i:s'));

            // Sleep for 60 seconds to ensure it doesn't run again in the same minute
            sleep(60);
        } else {
            // Sleep for 30 seconds before checking the time again
            echo "Not yet time to run, sleeping for 30 seconds...\n";
            sleep(30);
        }
    } catch (Exception $e) {
        $errorMsg = "Error in check_expired_bookings script: " . $e->getMessage();
        echo $errorMsg . "\n";
        error_log($errorMsg);
        // Sleep for a short time to avoid rapid error looping
        sleep(30);
    }
}