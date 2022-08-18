<?php

namespace George\HomeTask\Repositories\Articles;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;

interface ArticlesRepositoryInterface
{
    public function save(Article $article): void;
    public function get(UUID $uuid): Article;
    public function getByTitle(string $title): Article;
    public function getByAuthor(UUID $id):Article;
    public function deleteById(UUID $id);
}