<?php

namespace George\HomeTask\Repositories\Articles;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class SqLiteArticleRepo implements ArticlesRepositoryInterface
{
    private PDO $connection;
    private LoggerInterface $logger;

    /**
     * @param PDO $connection
     * @param LoggerInterface $logger
     */
    public function __construct(PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(Article $article): void
    {
        $this->logger->info("Started saving the article to database");
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
     * @throws InvalidArgumentException
     * @throws ArticleNotFoundException
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
     * @throws InvalidArgumentException
     * @throws ArticleNotFoundException
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
     * @throws InvalidArgumentException
     * @throws ArticleNotFoundException
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
     * @throws InvalidArgumentException
     * @throws ArticleNotFoundException
     */
    private function getArticle(PDOStatement $statement, string $title): Article
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find the article by $title");
            throw new ArticleNotFoundException(
                "Cannot find article: $title"
            );
        }
        // Создаём объект пользователя с полем username
        return new Article(new UUID($result['uuid']), new UUID($result['authorUuid']), $result['title'], $result['text']);
    }

    public function deleteById(UUID $id){
        $statement = $this->connection->prepare('DELETE FROM articles WHERE uuid = :id');

        $statement->execute([
            ':id' => (string)$id,
        ]);
    }
}