<?php

use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\CommentNotFoundException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Repositories\Comments\SqLiteCommentsRepo;
use George\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class CommentRepoTest extends TestCase
{
    public function testItSavesArticleToDatabase():void{
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method("prepare")->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with([
                ':uuid' => "123e4567-e89b-12d3-a456-426614174000" ,
                ':authorId' =>"123e4567-e89b-12d3-a456-426614174001" ,
                ':articleId' => "123e4567-e89b-12d3-a456-426614174002",
                ':text' => "text"]
        )->willReturn(true);

        $sqlRepo = new SqLiteCommentsRepo($connectionMock, new DummyLogger());
        // Свойства пользователя точно такие,как и в описании мока
        $sqlRepo->save(new Comment(
            new UUID("123e4567-e89b-12d3-a456-426614174000"),
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            new UUID("123e4567-e89b-12d3-a456-426614174002"),
            "text"));
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException|UserNotFoundException
     */
    public function testExceptionWhenUserNotFound(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with(
            [ // Даём понятьь что метод execute вызовется лишь 1 раз с единственным аргументом - массивом
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000']
        );
        $statementMock->method('fetch')->willReturn(false);

        $sqlRepo=new SqLiteCommentsRepo($connectionMock, new DummyLogger());

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage("Cannot find comment: 123e4567-e89b-12d3-a456-426614174000");

        $sqlRepo->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function testGetCommentByAuthor(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->method('fetch')->willReturn([
            'uuid'=>'123e4567-e89b-12d3-a456-426614174000',
            'authorId'=>'123e4567-e89b-12d3-a456-426614174001',
            'articleId'=>"123e4567-e89b-12d3-a456-426614174002",
            'text'=> 'text'
        ]);

        $sqlRepo=new SqLiteCommentsRepo($connectionMock, new DummyLogger());

        $user = new Comment(
            new UUID("123e4567-e89b-12d3-a456-426614174000"),
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            new UUID("123e4567-e89b-12d3-a456-426614174002"),
            "text");

        $value = $sqlRepo->getByAuthor(new UUID("123e4567-e89b-12d3-a456-426614174002"));

        $this->assertEquals($user, $value);
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function testGetCommentByArticle(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->method('fetch')->willReturn([
            'uuid'=>'123e4567-e89b-12d3-a456-426614174000',
            'authorId'=>'123e4567-e89b-12d3-a456-426614174001',
            'articleId'=>"123e4567-e89b-12d3-a456-426614174002",
            'text'=> 'text'
        ]);

        $sqlRepo=new SqLiteCommentsRepo($connectionMock, new DummyLogger());

        $user = new Comment(
            new UUID("123e4567-e89b-12d3-a456-426614174000"),
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            new UUID("123e4567-e89b-12d3-a456-426614174002"),
            "text");

        $value = $sqlRepo->getByArticle(new UUID("123e4567-e89b-12d3-a456-426614174001"));

        $this->assertEquals($user, $value);
    }
}