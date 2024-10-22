<?php

namespace Src\Patterns;

use Doctrine\DBAL\Connection;

abstract class DbSingleton
{
    private static array $instances = array();

    public static function get_instance(Connection $conn, array $params)
    {
        $class = get_called_class();
        if (array_key_exists($class, self::$instances) === false) {
            self::$instances[$class] = new $class($conn, $params);
        }
        return self::$instances[$class];
    }
}