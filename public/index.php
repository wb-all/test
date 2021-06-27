<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Common\Application\Http\MainAction;
use App\Comment\Application\Http\Action\CommentListAction;
use App\Comment\Application\Http\Action\CommentCreatorAction;
use App\Comment\Infrastructure\Repository\CommentRepository;
use App\Comment\Infrastructure\Repository\CommentRepositoryInterface;
use App\Comment\Infrastructure\Service\CommentService;
use League\Container\Container;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Laminas\Diactoros\ResponseFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$container = new Container;

// @todo: extract to configurations
$container->add(CommentListAction::class)->addArgument(CommentRepositoryInterface::class);
$container->add(CommentCreatorAction::class)
    ->addArgument(CommentService::class)
    ->addArgument(CommentRepositoryInterface::class);

$db = new \PDO('mysql:host=mysql;dbname=test;charset=utf8mb4', 'aleksandr', '123321!', [
    \PDO::ATTR_EMULATE_PREPARES => false,
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
]);

$container->add(CommentService::class, CommentService::class)
    ->addArgument($db);

$container->add(CommentRepositoryInterface::class, CommentRepository::class)
    ->addArgument($db);

$strategy = new JsonStrategy(
    new ResponseFactory()
);

$strategy->setContainer($container);

$router   = new Router();
$router->setStrategy($strategy);

// @todo: extract to configurations
$router
    ->get('/', MainAction::class)
    ->setStrategy(new ApplicationStrategy());

$router->get('/api/topics/{id}/comments', CommentListAction::class);
$router->post('/api/topics/{id}/comments', CommentCreatorAction::class);

$response = $router->dispatch($request);

(new SapiEmitter)->emit($response);