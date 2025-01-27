<?php

namespace DaguConnect\Services;

trait ValidatePhoneNumber
{
    public function validatePhoneNumber($phone_number):bool{
        // Check if the phone number contains only numbers
        if (preg_match('/^\d+$/', $phone_number)) {
            return true; // Valid phone number
        }
        return false; // Invalid phone number
    }
}