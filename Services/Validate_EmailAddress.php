<?php

namespace DaguConnect\Services;

trait Validate_EmailAddress
{
    public function validateEmailAddress($email):bool{
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;

    }
}