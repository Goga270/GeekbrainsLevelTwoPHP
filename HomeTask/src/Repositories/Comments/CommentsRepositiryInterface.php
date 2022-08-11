<?php

namespace George\HomeTask\Repositories\Comments;

use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Common\UUID;

interface CommentsRepositiryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
    public function getByAuthor(UUID $id): Comment;
    public function getByArticle(UUID $id): Comment;
}