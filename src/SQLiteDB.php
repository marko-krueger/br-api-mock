<?php
declare(strict_types=1);

namespace Thomann\BrMockServer;


use PDO;

class SQLiteDB
{
    private $pdo;

    public function __construct(string $dbFile, Migration $migration, bool $migrate)
    {
        $this->connect($dbFile);

        if ($migrate) {
            $migration->run($this->pdo);
        }
    }


    private function connect($filename): void
    {
        $this->pdo = new PDO('sqlite:' . $filename);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function query($sql, $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollback();
    }

    public function close()
    {
        $this->pdo = null;
    }
}