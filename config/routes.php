<?php
declare(strict_types=1);

use NC\Routing\RoutingConfigurator;
use Thomann\BrMockServer\Http\ApiController;
use Thomann\BrMockServer\Http\IndexController;

return static function (RoutingConfigurator $routes): void {
    $routes->add('index', '/')
        ->controller([IndexController::class, 'index']);
    $routes->add('api.ingest', '/api/accounts/{accountId}/catalogs/{catalogName}/products')
        ->controller([ApiController::class, 'ingest'])
        ->methods(['PUT']);
    $routes->add('api.status', '/api/jobs/{jobId}')->controller([ApiController::class, 'status']);
};