<?php

namespace George\HomeTask\Repositories\Comments;

use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\UserNotFoundException;

class InMemoryCommentsRepo implements CommentsRepositiryInterface
{
    private array $comments = [];
    public function save(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Comment
    {
        foreach ($this->comments as $comment) {
            if ((string)$comment->getId() === (string)$uuid) {
                return $comment;
            }
        }
        throw new UserNotFoundException("Article not found: $uuid");
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByAuthor(UUID $id): Comment
    {
        foreach ($this->comments as $comment) {
            if ((string)$comment->getAuthorId() === (string)$id) {
                return $comment;
            }
        }
        throw new UserNotFoundException("Article not found: $id");
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByArticle(UUID $id): Comment
    {
        foreach ($this->comments as $comment) {
            if ((string)$comment->getArticleId() === (string)$id) {
                return $comment;
            }
        }
        throw new UserNotFoundException("Article not found: $id");
    }
}