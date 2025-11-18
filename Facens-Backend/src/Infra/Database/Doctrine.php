<?php

namespace App\Infra\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, Events, ORMSetup};


class Doctrine
{
    private static $instance;

    private $conn = null;


    public function __construct()
    {
        $this->connect();
    }

    public function connect(): void
    {

        $rootApp = __DIR__ . "/../../../src/Infra/Database/EntitiesOrm";

        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [$rootApp],
            isDevMode: true,
        );
        $config->addCustomStringFunction('match', 'DoctrineExtensions\Query\Mysql\MatchAgainst');
        $config->addCustomStringFunction('month', 'DoctrineExtensions\Query\Mysql\Month');
        $config->addCustomStringFunction('year', 'DoctrineExtensions\Query\Mysql\Year');
        $config->addCustomStringFunction('unixTimestamp', 'DoctrineExtensions\Query\Mysql\UnixTimestamp');

        
        $host     = is_file("/.dockerenv") || !in_array($_ENV['ENV'], ['local']) ? $_ENV['MYSQL_HOST'] : "127.0.0.1";
        $database = $_ENV['MYSQL_DATABASE'];
        $username = $_ENV['MYSQL_USER'];
        $password = $_ENV['MYSQL_PASSWORD'];

        $connectionParams = [
            'driver'   => 'pdo_mysql',
            'user'     => $username,
            'password' => $password,
            'dbname'   => $database,
            'host'     => $host,
            'port'     => 3306
        ];

        $connection = DriverManager::getConnection(
            $connectionParams,
            $config
        );
        $em = new EntityManager($connection, $config);        

        $this->conn = $em;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->conn;
    }


    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new self;
        return self::$instance;
    }
}
