<?php
declare(strict_types=1);

use NC\Routing\Factory\RouterFactory;
use NC\Routing\Middleware\MiddlewareFactory;
use NC\Routing\Middleware\RequestPipe;
use NC\Routing\Request;
use NC\Routing\RequestHandler;
use NC\Routing\RequestProcessor;

$container = require __DIR__ . '/config/bootstrap.php';

try {

    $request = Request::make();

    $container->set(Request::class, $request);

    $router = (new RouterFactory($container))
        ->createFromConfig(__DIR__ . '/config/routes.php', null);

    $requestProcessor = new RequestProcessor(
        $router,
        new RequestHandler($container),
        new MiddlewareFactory($container, new RequestPipe()),
    );

    return $requestProcessor->process($request)->send();

} catch (Exception $e) {
    print '<pre>';
    print $e->getMessage();
    print '</pre>';
}
