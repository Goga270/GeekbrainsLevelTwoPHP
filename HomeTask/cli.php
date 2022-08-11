<?php
require_once __DIR__."/vendor/autoload.php";
$con = new PDO('sqlite:'.__DIR__.'/../blog.sqlite');

use George\HomeTask\Blog\Article\CreateArticleCommand;
use George\HomeTask\Blog\Comment\Comment;
use  \George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Comment\CreateCommentCommand;
use George\HomeTask\Blog\User\CreateUserCommand;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\AppException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use George\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use George\HomeTask\Repositories\Users\InMemoryUsersRepo;
use George\HomeTask\Repositories\Users\SqLiteUserRepo;
use George\HomeTask\Exceptions\InvalidArgumentException;

try{
    //Создаём репозиторий для сохранения в базу данных, но с помощью интерфейса можно легко
    // переключить работу, на с охранение данных локально в программе
    $userDbRepo = new SqLiteUserRepo($con);
    $articleDbRepo = new SqLiteArticleRepo($con);
    $commentDbRepo = new SqLiteCommentsRepo($con);
    if(count($argv)>0){
        //получаем ассоциативный массив из командной строки с аргументами
        $arguments = Arguments::fromArgv($argv);

        //создаём класс для создания пользователей.
        /*$createUser = new CreateUserCommand($userDbRepo);
        $createUser->handle($arguments);*/

        $user=$userDbRepo->getByUsername($arguments->getArg('username'));
        /*$createArticle = new CreateArticleCommand($articleDbRepo);
        $createArticle->handle($arguments, $user->getId());*/

        $article2 = $articleDbRepo->getByAuthor($user->getId());
        $article = $articleDbRepo->getByTitle('IgorLox');
        $createComment = new CreateCommentCommand($commentDbRepo);
        //$createComment->handle($arguments,$user->getId(), $article->getId());


    }
}catch (AppException $e){
    echo $e->getMessage();
}




