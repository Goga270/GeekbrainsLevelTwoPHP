<?php

namespace George\HomeTask\Blog\User;

use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;

class User
{
    private ?UUID$id;
    private ?Name $name;
    private ?string $username;

    /**
     * @param UUID|null $id
     * @param Name|null $name
     * @param string|null $username
     */
    public function __construct(?UUID $id,?string $username, ?Name $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return UUID|null
     */
    public function getId(): ?UUID
    {
        return $this->id;
    }

    /**
     * @param UUID|null $id
     */
    public function setId(?UUID $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Name|null
     */
    public function getName(): ?Name
    {
        return $this->name;
    }

    /**
     * @param Name|null $name
     */
    public function setName(?Name $name): void
    {
        $this->name = $name;
    }


    public function __toString(): string{
        return ("id=".$this->id.", "."name=".$this->name->getFirstName().", "."lastname=".$this->name->getLastName().", username=".$this->username.PHP_EOL);
    }
}