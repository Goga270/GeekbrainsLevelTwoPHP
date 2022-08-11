<?php

namespace George\HomeTask\Repositories\Articles;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;

class SqLiteArticleRepo implements ArticlesRepositoryInterface
{
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Article $article): void
    {
        // Добавили поле username в запрос
        $statement = $this->connection->prepare(
            'INSERT INTO articles (uuid, authorUuid, title, text)
            VALUES (:uuid, :authorUuid, :title, :text)'
        );
        $statement->execute([
            ':uuid' => (string)$article->getId(),
            ':authorUuid' => $article->getAuthorId(),
            ':title' =>$article->getTitle(),
            ':text' => $article->getText()]);
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM articles WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getArticle($statement, $uuid);
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function getByTitle(string $title): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM articles WHERE title = :title'
        );
        $statement->execute([
            ':title' => $title,
        ]);
        return $this->getArticle($statement, $title);
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    public function getByAuthor(UUID $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM articles WHERE authorUuid = :authorId'
        );
        $statement->execute([
            ':authorId' => (string)$id,
        ]);
        return $this->getArticle($statement, $id);
    }

    // Вынесли общую логику в отдельный приватный метод

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws UserNotFoundException
     */
    private function getArticle(PDOStatement $statement, string $title): Article
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find article: $title"
            );
        }
        // Создаём объект пользователя с полем username
        return new Article(new UUID($result['uuid']), new UUID($result['authorUuid']), $result['title'], $result['text']);
    }
}