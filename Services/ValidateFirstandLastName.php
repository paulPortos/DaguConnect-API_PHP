<?php

namespace DaguConnect\Services;

trait ValidateFirstandLastName
{
    public function validateFirstAndLastName($first_name, $last_name): bool
    {
        // Validate first name and last name
        if (!preg_match("/^[a-zA-Z ]*$/", $first_name) || !preg_match("/^[a-zA-Z ]*$/", $last_name)) {
            return false;
        }
        return true;
    }
}
