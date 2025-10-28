<?php

require 'vendor/autoload.php';

use App\Infra\Database\Doctrine;
use App\Infra\ThirdParty\Env\PhpDotEnvAdapter;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;

PhpDotEnvAdapter::load(__DIR__ . '/');

return DependencyFactory::fromEntityManager(
    new ConfigurationArray(
        [
            'table_storage' => [
                'table_name'                 => 'doctrineMigrationVersions',
                'version_column_name'        => 'version',
                'version_column_length'      => 191,
                'executed_at_column_name'    => 'executedAt',
                'execution_time_column_name' => 'executionTime',
            ],

            'migrations_paths'        => [
                'Migrations' => './src/Infra/Database/Migrations'
            ],
            'all_or_nothing'          => true,
            'transactional'           => true,
            'check_database_platform' => true,
            'organize_migrations'     => 'none',
        ]
    ),
    new ExistingEntityManager(Doctrine::getInstance()->getEntityManager())
);
