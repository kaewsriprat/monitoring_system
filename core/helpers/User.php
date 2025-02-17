<?php

class User
{
    public static function isLogin()
    {	
        return isset($_SESSION['user']);
    }

    public static function isAdmin()
    {
        return in_array(1, self::roles());
    }

    public static function isUser()
    {
        return in_array(2, self::roles());
    }
    
    public static function isDirector() {
        return in_array(3, self::roles());
    }
    
    public static function id()
    {
        return $_SESSION['user']['id'];
    }

    public static function division()
    {
        return $_SESSION['user']['division_id'];
    }

    public static function email()
    {
        return $_SESSION['user']['email'];
    }
    
    public static function roles()
    {
        return $_SESSION['user']['roles'];
    }

}
