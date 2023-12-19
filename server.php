<?php
declare(strict_types=1);

use NC\Routing\Factory\RouterFactory;
use NC\Routing\Middleware\MiddlewareFactory;
use NC\Routing\Middleware\RequestPipe;
use NC\Routing\Request;
use NC\Routing\RequestHandler;
use NC\Routing\RequestProcessor;
use Thomann\BrMockServer\DI\Container;
use Thomann\BrMockServer\Http\ApiController;
use Thomann\BrMockServer\Http\IndexController;

require __DIR__ . '/vendor/autoload.php';

$container = Container::getInstance();
$container->add('logDir', __DIR__ . '/logs');
$container->add(IndexController::class, new IndexController());
$container->add(ApiController::class, new ApiController($container->get('logDir')));

try {

    $request = Request::make();
    $container->add(Request::class, $request);

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
