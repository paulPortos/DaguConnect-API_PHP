<?php

namespace DaguConnect\Services;

trait Trim
{
    public function TrimPassword($password): bool
    {
        $TrimmedPass = trim($password);

        // Check if the length of the trimmed password is at least 6 characters
        if (strlen($TrimmedPass) < 6) {
            return false;
        }
        return true;
    }

    public function TrimFirstName($first_name): bool{
        $TrimmedFirstName = trim($first_name);
        // Check if the length of the trimmed firstname is at least 2 character
        if (strlen($TrimmedFirstName) < 2) {
            return false;
        }
        return true;
    }

    public function TrimLastName($first_name): bool{
        $TrimmedLastName = trim($first_name);
    // Check if the length of the trimmed lastname is at least 2 character
        if (strlen($TrimmedLastName) < 2) {
            return false;
        }
        return true;
    }


}
