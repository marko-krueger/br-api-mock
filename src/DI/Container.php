<?php
declare(strict_types=1);

namespace Thomann\BrMockServer\DI;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private static Container $instance;

    private array $instances = [];

    public static function getInstance(): Container
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function add(string $id, $instance): void
    {
        $this->instances[$id] = $instance;
    }

    public function get(string $id)
    {
        return $this->instances[$id] ?? null;
    }

    public function has(string $id)
    {
        return isset($this->instances[$id]);
    }
}