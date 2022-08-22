<?php

use George\HomeTask\Container\DIContainer;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use George\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use George\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use George\HomeTask\Repositories\Likes\SqLiteLikesRepo;
use George\HomeTask\Repositories\Users\SqLiteUserRepo;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/blog.sqlite');

$container = new DIContainer();

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
$container->bind(
    LikesRepositoryInterface::class,
    SqLiteLikesRepo::class
);
// Возвращаем объект контейнера
return $container;
