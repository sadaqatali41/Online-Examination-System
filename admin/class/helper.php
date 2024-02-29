<?php

class Helper
{
    public function passwordReplace(string $password) : string {
        $length = strlen($password);
        $hashPassword = str_repeat('#', $length);
        return $hashPassword;
    }
}