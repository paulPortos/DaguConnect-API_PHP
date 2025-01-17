<?php

namespace DaguConnect\Services;

trait Trim_Password
{
    public function trimPassword($password):bool{
        $TrimmedPass = trim($password);
        if (!$TrimmedPass == 6) {
            return false;
        }
        return true;
    }

}