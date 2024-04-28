<?php

namespace App\Service;

class VerificationCodeGenerator
{

    public static function generateVerificationCode($user)
    {
        // Generate a hash code based on the user information
        $hashCode = md5(serialize($user)); // Using md5 for simplicity, you might choose a different hashing algorithm

        // Generate a random salt
        $randomSalt = self::generateRandomSalt();

        // Combine hash code and random salt
        $combinedString = $hashCode . $randomSalt;

        // Hash the combined string using SHA-256
        $hashedString = self::hashString($combinedString);

        // Extract a subset of the hash value as the verification code
        return substr($hashedString, 0, 6);
    }

    private static function generateRandomSalt()
    {
        // Generate a random salt using openssl_random_pseudo_bytes
        $salt = openssl_random_pseudo_bytes(16);
        return bin2hex($salt);
    }

    private static function hashString($input)
    {
        // Hash the input string using SHA-256
        return hash('sha256', $input);
    }
}
