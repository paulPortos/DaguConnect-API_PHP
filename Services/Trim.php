<?php

namespace DaguConnect\Services;

trait Trim
{
    public static function trimPassword($password): bool
    {
        $TrimmedPass = trim($password);

        // Check if the length of the trimmed password is at least 6 characters
        if (strlen($TrimmedPass) < 6) {
            return false;
        }
        return true;
    }

    public static function trimFirstName($first_name): bool{
        $TrimmedFirstName = trim($first_name);
        // Check if the length of the trimmed firstname is at least 2 character
        if (strlen($TrimmedFirstName) < 2) {
            return false;
        }
        return true;
    }

    public static function trimLastName($first_name): bool{
        $TrimmedLastName = trim($first_name);
    // Check if the length of the trimmed lastname is at least 2 character
        if (strlen($TrimmedLastName) < 2) {
            return false;
        }
        return true;
    }


}
