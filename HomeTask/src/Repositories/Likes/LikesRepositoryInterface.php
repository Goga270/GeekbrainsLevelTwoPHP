<?php

namespace George\HomeTask\Repositories\Likes;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Like\Like;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $comment): void;
    public function get(UUID $uuid): Like;
    public function getByAuthor(UUID $id): Like;
    public function getByArticle(UUID $id): Like;
    public function getAllByAuthor(UUID $id): iterable;
    public function getAllByArticle(UUID $id):iterable;
    public function likeExist(Article $article, User $user):void;
}