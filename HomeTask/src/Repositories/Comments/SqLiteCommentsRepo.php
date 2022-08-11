<?php

namespace George\HomeTask\Repositories\Comments;

use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;

class SqLiteCommentsRepo implements CommentsRepositiryInterface {

    //Поле где хранится конект к базе данных с данными.
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Comment $comment): void
    {
        // Добавили поле username в запрос
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, authorId, articleId, text)
            VALUES (:uuid, :authorId, :articleId, :text)'
        );
        $statement->execute([
            ':uuid' => (string)$comment->getId(),
            ':authorId' => $comment->getAuthorId(),
            ':articleId' => $comment->getArticleId(),
            ':text' => $comment->getText()]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        return $this->getComment($statement, $uuid);
    }

    /**
     * @throws UserNotFoundException
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     */
    public function getByAuthor(UUID $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE authorId = :authorId'
        );
        $statement->execute([
            ':authorId' => (string)$id,
        ]);
        return $this->getComment($statement, "by authorId".$id);
    }

    /**
     * @throws UserNotFoundException
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     */
    public function getByArticle(UUID $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE articleId = :articleId'
        );
        $statement->execute([
            ':articleId' => (string)$id,
        ]);
        return $this->getComment($statement, "by articleId".$id);
    }

    /**
     * @throws UserNotFoundException
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     */
    private function getComment(PDOStatement $statement, string $id): Comment
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find comment: $id"
            );
        }
        // Создаём объект пользователя с полем username
        return new Comment(new UUID($result['uuid']), new UUID($result['authorId']), new UUID($result['articleId']), $result['text']);
    }
}