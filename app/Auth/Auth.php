<?php


namespace Auth;


use Models\Manager;

class Auth
{
    public static function id()
    {
        return $_SESSION['id'];
    }

    public static function check()
    {
        return isset($_SESSION['id']);
    }

    public static function attempt($account, $password)
    {
        $manager = Manager::where('account', $account);

        if (!$manager) return false;

        if (!password_verify($password, $manager->password)) return false;

        $_SESSION['id'] = $manager->id;
        return true;
    }

    public static function logout()
    {
        unset($_SESSION['id']);
    }
}