<?php

namespace App\Library;


class Cookie
{
    const COOKIE_ID = 'moviemarks';

    private static $password = '';
    private static $username = '';

    public static function getUsername()
    {
        if (!self::$username && isset($_COOKIE[self::COOKIE_ID])) {
            self::$username = $_COOKIE[self::COOKIE_ID];
        }
        return self::$username;
    }

    public static function init()
    {
        self::$username = md5(time() . self::COOKIE_ID);
        self::$password = md5(self::$username . $_SERVER['HTTP_USER_AGENT']);
        setcookie(self::COOKIE_ID, self::$username, time()+60*60*24*90);
    }

    public static function getPassword()
    {
        return self::$password;
    }
}