<?php

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Repositories\Articles\SqLiteArticleRepo;
use George\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class ArticleRepoTest extends TestCase
{
    public function testItSavesArticleToDatabase():void{
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method("prepare")->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with([
            ':uuid' => "123e4567-e89b-12d3-a456-426614174000",
            ':authorUuid' => "123e4567-e89b-12d3-a456-426614174001",
            ':title' =>"title",
            ':text' =>"text" ]
        )->willReturn(true);

        $sqlRepo = new SqLiteArticleRepo($connectionMock, new DummyLogger());
        // Свойства пользователя точно такие,как и в описании мока
        $sqlRepo->save(new Article(
            new UUID("123e4567-e89b-12d3-a456-426614174000"),
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            "title",
            "text")
        );
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

        $sqlRepo=new SqLiteArticleRepo($connectionMock, new DummyLogger());

        $this->expectException(ArticleNotFoundException::class);
        $this->expectExceptionMessage("Cannot find article: 123e4567-e89b-12d3-a456-426614174000");

        $sqlRepo->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function testGetArticleByTitle(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with(
            [ // Даём понятьь что метод execute вызовется лишь 1 раз с единственным аргументом - массивом
                ':title' => 'title']
        );
        $statementMock->method('fetch')->willReturn([
            'uuid'=>'123e4567-e89b-12d3-a456-426614174000',
            'authorUuid'=>'123e4567-e89b-12d3-a456-426614174001',
            'title'=>'title',
            'text'=> 'text'
        ]);

        $sqlRepo=new SqLiteArticleRepo($connectionMock, new DummyLogger());

        $user = new Article(
            new UUID("123e4567-e89b-12d3-a456-426614174000"),
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            "title",
            "text");

        $value = $sqlRepo->getByTitle('title');

        $this->assertEquals($user, $value);
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function testGetArticleByAuthor(){
        $connectionMock = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionMock->method('prepare')->willReturn($statementMock);

        $statementMock->expects($this->once())->method('execute')->with(
            [ // Даём понятьь что метод execute вызовется лишь 1 раз с единственным аргументом - массивом
                ':authorId' => '123e4567-e89b-12d3-a456-426614174001']
        );
        $statementMock->method('fetch')->willReturn([
            'uuid'=>'123e4567-e89b-12d3-a456-426614174000',
            'authorUuid'=>'123e4567-e89b-12d3-a456-426614174001',
            'title'=>'title',
            'text'=> 'text'
        ]);

        $sqlRepo=new SqLiteArticleRepo($connectionMock, new DummyLogger());

        $user = new Article(
            new UUID("123e4567-e89b-12d3-a456-426614174000"),
            new UUID("123e4567-e89b-12d3-a456-426614174001"),
            "title",
            "text");

        $value = $sqlRepo->getByAuthor(new UUID("123e4567-e89b-12d3-a456-426614174001"));

        $this->assertEquals($user, $value);
    }
}