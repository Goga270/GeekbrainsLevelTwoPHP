<?php

/*spl_autoload_register(function ($name) {
    $os = php_uname('s');
    if(!(strpos($os, "Windows"))){
        $name = str_replace("George\Lesson1", "", $name);
    }elseif (!(strpos($os, "Linux"))){
        $name = str_replace("George\Lesson1", "", $name);
        $name = str_replace("\\", "/", $name );
    }
    include __DIR__."/src".$name.".php";
});*/

require_once __DIR__."/vendor/autoload.php";

use George\Lesson1\Blog\Comment\Comment;
use  \George\Lesson1\Blog\Person\Person;
use  \George\Lesson1\Blog\Article\Article;

function loader($name){
    $os = php_uname('s');
    if(!(strpos($os, "Windows"))){
        $name = str_replace("_", "\\", $name);
    }elseif (!(strpos($os, "Linux"))){
        $name = str_replace("\\", "/", $name );
        $name = str_replace("_", "/", $name);
    }
    echo __DIR__.$name.".php".PHP_EOL;
}

function main($type){
    $faker = Faker\Factory::create();
    switch ($type){
        case "user":
            $user = new Person(1, $faker->firstName, $faker->lastName);
            echo $user;
            break;
        case "post":
            $post = new Article(1, 1,$faker->title, $faker->text);
            echo $post;
            break;
        case "comment":
            $comment = new Comment(1, 1,1, $faker->text);
            echo $comment;
            break;
    }
}

echo "USERS".PHP_EOL;
$user1 = new Person();
echo $user1.PHP_EOL;
$user2 = new George\Lesson1\Blog\Person\Person(2, "George", "Aladin");
echo $user2.PHP_EOL.PHP_EOL;
//
//echo "ARTICLES".PHP_EOL;
//$article1 = new Article();
//echo $article1.PHP_EOL;
//$article2 = new Article(1, $user2->getId(),"Title", "lorem ipsum");
//echo $article2.PHP_EOL.PHP_EOL;
//
//echo "Comments".PHP_EOL;
//$comment1 = new Comment();
//echo $comment1.PHP_EOL;
//$comment2 = new Comment(1, $user2->getId(), $article2->getId(), "lorem ipsum");
//echo $comment2.PHP_EOL;

if(count($argv)>2){
    for($i=1;$i<count($argv); $i++){
        main($argv[$i]);
    }
}else{
    main($argv[1]);
}


