<?php

namespace DaguConnect\Services;

trait TrimPassword
{
    public function trimPassword($password): bool
    {
        $TrimmedPass = trim($password);

        // Check if the length of the trimmed password is at least 6 characters
        if (strlen($TrimmedPass) < 6) {
            return false;
        }
        return true;
    }
}
