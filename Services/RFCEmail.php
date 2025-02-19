<?php

namespace DaguConnect\Services;

trait RFCEmail
{
    public static function checkEmail($email): bool
    {
        // Trim whitespace from the email
        $email = trim($email);

        // Check if email is empty
        if (empty($email)) {
            return false;
        }

        // Google-like relaxed RFC email regex
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        // Check if the email matches the pattern
        if (!preg_match($pattern, $email)) {
            return false;
        }

        // Ensure domain has at least one dot and does not start or end with it
        $domain = substr(strrchr($email, "@"), 1);
        if (substr_count($domain, '.') < 1 || str_starts_with($domain, '.') || str_ends_with($domain, '.')) {
            return false;
        }

        // Ensure local part does not start or end with a dot
        $localPart = strstr($email, '@', true);
        if (str_starts_with($localPart, '.') || str_ends_with($localPart, '.')) {
            return false;
        }

        // Ensure the domain part is not purely numeric (to match Google's behavior)
        if (is_numeric(str_replace('.', '', $domain))) {
            return false;
        }

        // Ensure no consecutive dots (e.g., "test..email@gmail.com" is invalid)
        if (str_contains($email, '..')) {
            return false;
        }

        return true;
    }
}
