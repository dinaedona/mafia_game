<?php
require_once '../Model/Response.php';
class PasswordValidator
{

    public function validate($password): Response
    {
        // Defined regular expressions for password criteria
        $upperCase = '/[A-Z]/';
        $number = '/[0-9]/';
        $specialChar = '/[!@#$%^&*()\-_=+{};:,<.>]/';
        $minLength = 8;

        if (strlen($password) < $minLength) {
            return Response::create(0, "Password must be at least {$minLength} characters long.");
        }
        if (!preg_match($upperCase, $password)) {
            return Response::create(0, 'Password must contain at least one uppercase letter.');
        }
        if (!preg_match($number, $password)) {
            return Response::create(0, 'Password must contain at least one number.');
        }
        if (!preg_match($specialChar, $password)) {
            return Response::create(0, 'Password must contain at least one special character.');
        }
        return Response::create(1);
    }
}