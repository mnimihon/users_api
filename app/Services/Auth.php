<?php

namespace app\Services;

use app\Module\Tokens;

class Auth
{
    public static function validateToken()
    {
        return (new Tokens)->getByConditions(['token' => self::getBearerToken()]);
    }

    public static function generateToken(): string
    {
        return md5(time());
    }

    public static function getBearerToken(): string
    {
        if (preg_match('/Bearer\s(\S+)/', getallheaders()['Authorization'], $matches)) {
            return $matches[1];
        }
        return '';
    }
}