<?php

namespace DaguConnect\Services;

trait Confirm_Password
{
    public function checkPassword($password, $confirm_password): bool {
        if ($password == $confirm_password) {
            return true;
        }
        return false;
    }
}