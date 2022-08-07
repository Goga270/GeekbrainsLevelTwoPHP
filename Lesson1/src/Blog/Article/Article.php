<?php

namespace George\Lesson1\Blog\Article;

class Article
{
    private ?int $id;
    private ?int $authorId;
    private ?string $title;
    private ?string $text;

    /**
     * @param int|null $id
     * @param int|null $authorId
     * @param string|null $title
     * @param string|null $text
     */
    public function __construct(?int $id = null, ?int $authorId = null, ?string $title=null, ?string $text=null)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
        return ("id=".$this->id.", "."authorId=".$this->authorId.", "."title=".$this->title.", "."text=".$this->text.PHP_EOL);
    }


}