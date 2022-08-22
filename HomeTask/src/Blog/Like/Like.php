<?php

namespace George\HomeTask\Blog\Like;

use George\HomeTask\Common\UUID;

class Like
{
    private UUID $like;
    private UUID $article;
    private UUID $user;

    /**
     * @param UUID $like
     * @param UUID $article
     * @param UUID $user
     */
    public function __construct(UUID $like, UUID $article, UUID $user)
    {
        $this->like = $like;
        $this->article = $article;
        $this->user = $user;
    }

    /**
     * @return UUID
     */
    public function getLike(): UUID
    {
        return $this->like;
    }

    /**
     * @param UUID $like
     */
    public function setLike(UUID $like): void
    {
        $this->like = $like;
    }

    /**
     * @return UUID
     */
    public function getArticle(): UUID
    {
        return $this->article;
    }

    /**
     * @param UUID $article
     */
    public function setArticle(UUID $article): void
    {
        $this->article = $article;
    }

    /**
     * @return UUID
     */
    public function getUser(): UUID
    {
        return $this->user;
    }

    /**
     * @param UUID $user
     */
    public function setUser(UUID $user): void
    {
        $this->user = $user;
    }

    public function __toString(): string{
        return ("id=".$this->like.", "."articleId=".$this->article.", "."userId=".$this->user.PHP_EOL);
    }


}