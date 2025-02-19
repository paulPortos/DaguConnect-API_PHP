<?php

namespace DaguConnect\Services;

trait ValidateEmailAddress
{
    public function validateEmailAddress($email):bool{
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
}