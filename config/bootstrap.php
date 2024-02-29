<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Thomann\BrMockServer\Migration;
use Thomann\BrMockServer\SQLiteDB;

use function DI\autowire;
use function DI\get;

require dirname(__DIR__) . '/vendor/autoload.php';
$dbFile = dirname(__DIR__) . '/logs/jobs.db';

$di = [
    SQLiteDB::class => autowire()
        ->constructorParameter('dbFile', $dbFile)
        ->constructorParameter('migrate', !file_exists($dbFile)),
    Migration::class => autowire(Migration::class)->constructor(get('migration')),
    'migration' => [
        'jobs' => [
            'id' => 'VARCHAR(55) PRIMARY KEY',
            'status' => 'VARCHAR(55)',
            'account_id' => 'VARCHAR(55)',
            'catalog_name' => 'VARCHAR(55)',
            'type' => 'VARCHAR(55)',
            'type_action' => 'VARCHAR(55)',
            'time' => 'INTEGER',
        ]
    ]
];


$builder = new ContainerBuilder();
$builder->addDefinitions($di);

try {
    return $builder->build();
} catch (Exception $e) {
}
