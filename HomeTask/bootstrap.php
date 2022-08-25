<?php

use Dotenv\Dotenv;
use George\HomeTask\Container\DIContainer;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use George\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use George\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use George\HomeTask\Repositories\Likes\SqLiteLikesRepo;
use George\HomeTask\Repositories\Users\SqLiteUserRepo;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

Dotenv::createImmutable(__DIR__)->safeLoad();
require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/'.$_SERVER['SQLITE_DB_PATH']);

$container = new DIContainer();

$logger = (new Logger('blog'));

if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.log'
    ))->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}
// Включаем логирование в консоль,
// если переменная окружения LOG_TO_CONSOLE
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger->pushHandler(
            new StreamHandler("php://stdout")
        );
}
$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/../blog.sqlite')
);

$container->bind(
    ArticlesRepositoryInterface::class,
    SqLiteArticleRepo::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqLiteUserRepo::class
);

$container->bind(
    CommentsRepositiryInterface::class,
    SqLiteCommentsRepo::class
);
// Возвращаем объект контейнера
return $container;
