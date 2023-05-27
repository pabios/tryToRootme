<?php

namespace Pabiosoft\App\Config;

class Key
{
    private static $config = [
        'SECRET_KEY' => 'your_secret_key',
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'secu',
        'DB_USERNAME' => 'pabios',
        'DB_PASSWORD' => 'pass',
    ];

    public static function getSecretKey()
    {
        return self::$config['SECRET_KEY'];
    }

    public static function getDbHost()
    {
        return self::$config['DB_HOST'];
    }
    public static function getDbName()
    {
        return self::$config['DB_NAME'];
    }

    public static function getDbUsername()
    {
        return self::$config['DB_USERNAME'];
    }

    public static function getDbPassword()
    {
        return self::$config['DB_PASSWORD'];
    }

}
