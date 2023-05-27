<?php

namespace Pabiosoft\App\Config;

class Env
{
    private static $config = [
        'SECRET_KEY' => [
            'key' => '8c4d00f041c970d8fcb4a562b078e3d1efa40736b7ac2372683592242e186951',
            'algo' => 'HS256',
        ],
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'secu',
        'DB_USERNAME' => 'pabios',
        'DB_PASSWORD' => 'pass',
    ];

    public static function getSecretKey()
    {
        return self::$config['SECRET_KEY']['key'];
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
