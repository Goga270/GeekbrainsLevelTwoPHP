<?php

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Article\CreateArticleCommand;
use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArgumentsException;
use George\HomeTask\Exceptions\CommandException;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class ArticleCommandClassTest extends TestCase
{
    private function getRepo()
    {
        return new class implements ArticlesRepositoryInterface {
            private bool $callback = false;
            public function save(Article $article): void
            {
                $this->callback = true;
            }


            public function getCallback(): bool
            {
                return $this->callback;
            }

            public function getByTitle(string $title): Article
            {
                // TODO: Implement getByTitle() method.
            }

            public function getByAuthor(UUID $id): Article
            {
                // TODO: Implement getByAuthor() method.
            }

            public function get(UUID $uuid): Article
            {
                // TODO: Implement get() method.
            }

            public function deleteById(UUID $id)
            {
                // TODO: Implement deleteById() method.
            }
        };
    }

    /**
     * @throws ArgumentsException
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     * @throws ArgumentsException
     */
    public function testItSavesUserToRepository():void{

        $obj = $this->getRepo();

        $userCom = new CreateArticleCommand($obj, new DummyLogger());

        $userCom->handle(
            new Arguments([
            'title' => 'title',
            'text' => 'text'
        ]),
            new UUID('123e4567-e89b-12d3-a456-426614174001'));

        $this->assertTrue($obj->getCallback());
    }
}