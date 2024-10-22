<?php

namespace Src\WpDoctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;

class WpOrm
{
    private static array $instances = array();
    private Connection $connection;
    private ?EntityManager $entityManager = null;

    /**
     * @return WpOrm
     */
    public static function get_instance(): WpOrm
    {
        $class = get_called_class();
        if (array_key_exists($class, self::$instances) === false) {
            self::$instances[$class] = new $class();
        }
        return self::$instances[$class];
    }

    /**
     * @param array $params
     * @return void
     * @throws Exception
     */
    public function init(array $params): void
    {
        $paths = [dirname(__FILE__, 2) . '/Entity'];

        $dbParams = [
            'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            'host' => $params['host'],
            'user' => $params['user'],
            'password' => $params['password'],
            'dbname' => $params['db'],
            'port' => $params['port'],
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, true);
        $this->connection = DriverManager::getConnection($dbParams, $config);

        try {
            $this->entityManager = new EntityManager($this->connection, $config);
        } catch (MissingMappingDriverImplementation $e) {
            error_log($e->getMessage());
        }
    }

    public function getManager(): ?EntityManager
    {
        return $this->entityManager;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}