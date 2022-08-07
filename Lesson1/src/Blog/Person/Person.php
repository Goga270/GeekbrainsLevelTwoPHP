<?php

namespace George\Lesson1\Blog\Person;

class Person
{
    private ?int $id;
    private ?string $firstName;
    private ?string $lastName;

    /**
     * @param int|null $id
     * @param string|null $firstName
     * @param string|null $lastName
     */
    public function __construct(?int $id = null, ?string $firstName = "User", ?string $lastName = "User")
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function __toString(): string{
        return ("id=".$this->id.", "."name=".$this->firstName.", "."lastname=".$this->lastName.PHP_EOL);
    }


}