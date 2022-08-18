<?php
require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/../blog.sqlite');

use George\HomeTask\Exceptions\AppException;
use George\HomeTask\Http\Actions\ArticleAction\CreateArticle;
use George\HomeTask\Http\Actions\ArticleAction\DeleteArticleById;
use George\HomeTask\Http\Actions\ArticleAction\FindArticleById;
use George\HomeTask\Http\Actions\CommentAction\CreateComment;
use George\HomeTask\Http\Actions\CommentAction\FindCommentById;
use George\HomeTask\Http\Actions\UserAction\CreateUser;
use George\HomeTask\Http\Actions\UserAction\FindByUsername;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use George\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use George\HomeTask\Repositories\Users\SqLiteUserRepo;

http_response_code(200);

$request = new Request($_GET, $_SERVER, file_get_contents("php://input"));

try {
    $path = $request->path();
} catch (\George\HomeTask\Exceptions\HttpException $exception) {
    (new ErorrResponse($exception->getMessage()))->send();
    return;
}

try {
    // Пытаемся получить HTTP-метод запроса
    $method = $request->method();
} catch (\George\HomeTask\Exceptions\HttpException $e) {
    // Возвращаем неудачный ответ,
    // если по какой-то причине
    // не можем получить метод
    (new ErorrResponse($e->getMessage()))->send();
    return;
}

$routes = [
// Добавили ещё один уровень вложенности
// для отделения маршрутов,
// применяемых к запросам с разными методами
    'GET' => [
        '/show/user' => new FindByUsername(
            new SqLiteUserRepo($con)
        ),
        '/show/article' => new FindArticleById(
            new SqLiteArticleRepo($con)
        ),
        '/show/comment' => new FindCommentById(
            new SqLiteCommentsRepo($con)
        )
    ],
    'POST' => [
        // Добавили новый маршрут
        '/create/user' => new CreateUser(
            new SqLiteUserRepo($con)
        ),
        '/create/article' => new CreateArticle(
            new SqLiteArticleRepo($con), new SqLiteUserRepo($con)
        ),
        '/create/comment' => new CreateComment(
            new SqLiteCommentsRepo($con)
        )
    ],
    'DELETE' => [
        '/delete/article' => new DeleteArticleById(
            new SqLiteArticleRepo($con)
        )
    ]
];

// Если у нас нет маршрутов для метода запроса -
// возвращаем неуспешный ответ
if (!array_key_exists($method, $routes)) {
    (new ErorrResponse('Method Not found'))->send();
    return;
}

// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    (new ErorrResponse('Route Not found'))->send();
    return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErorrResponse($e->getMessage()))->send();
}

$response->send();



