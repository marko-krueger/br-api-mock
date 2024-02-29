<?php
declare(strict_types=1);

namespace Thomann\BrMockServer;

use PDO;

class Migration
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run(PDO $connection): void
    {
        foreach ($this->config as $table => $columns) {
            $this->createTable($connection, $table, $columns);
        }
    }

    private function createTable(PDO $connection, string $tableName, array $columns): void
    {
        $columnDefs = [];
        foreach ($columns as $column => $type) {
            $columnDefs[] = "$column $type";
        }
        $columnDefsString = implode(', ', $columnDefs);
        $sql = "CREATE TABLE IF NOT EXISTS $tableName ($columnDefsString)";

        $connection->exec($sql);
    }

}