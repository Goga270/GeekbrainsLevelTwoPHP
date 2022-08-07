<?php

namespace George\Lesson1\Blog\Comment;

class Comment
{
    private ?int $id;
    private ?int $authorId;
    private ?int $articleId;
    private ?string $text;

    /**
     * @param int|null $id
     * @param int|null $authorId
     * @param int|null $articleId
     * @param string|null $text
     */
    public function __construct(?int $id=null, ?int $authorId=null, ?int $articleId=null, ?string $text=null)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->articleId = $articleId;
        $this->text = $text;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    /**
     * @param int|null $authorId
     */
    public function setAuthorId(?int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return int|null
     */
    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    /**
     * @param int|null $articleId
     */
    public function setArticleId(?int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function __toString(): string{
        return ("id=".$this->id.", "."authorId=".$this->authorId.", "."articleId=".$this->articleId.", "."text=".$this->text.PHP_EOL);
    }


}