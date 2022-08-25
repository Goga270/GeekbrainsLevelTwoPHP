<?php
require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/../blog.sqlite');

use George\HomeTask\Blog\Article\CreateArticleCommand;
use George\HomeTask\Blog\Comment\Comment;
use  \George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Comment\CreateCommentCommand;
use George\HomeTask\Blog\Like\Like;
use George\HomeTask\Blog\User\CreateUserCommand;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\SomeClass;
use George\HomeTask\Common\UUID;
use George\HomeTask\Container\DIContainer;
use George\HomeTask\Exceptions\AppException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use George\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use George\HomeTask\Repositories\Likes\SqLiteLikesRepo;
use George\HomeTask\Repositories\Users\InMemoryUsersRepo;
use George\HomeTask\Repositories\Users\SqLiteUserRepo;
use George\HomeTask\Exceptions\InvalidArgumentException;

$container = require_once __DIR__.'/bootstrap.php';
$command = $container->get(CreateUserCommand::class);
try{
    $command->handle(Arguments::fromArgv($argv));
    $likeRepo = new SqLiteLikesRepo($con);
}catch (AppException $e){
    echo $e->getMessage();
}




