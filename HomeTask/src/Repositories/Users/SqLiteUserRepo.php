<?php

namespace George\HomeTask\Repositories\Users;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class SqLiteUserRepo implements UsersRepositoryInterface
{
    //Поле где хранится конект к базе данных с данными.
    private PDO $connection;
    private LoggerInterface $logger;

    /**
     * @param PDO $connection
     * @param LoggerInterface $logger
     */
    public function __construct(PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }
    //метод сохранения данных в базу данных, получаем объект класса User/
    public function save(User $user):void{
        $this->logger->info("Started saving the user to database");
        // Добавили поле username в запрос
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, username, first_name, last_name)
            VALUES (:uuid, :username, :first_name, :last_name)'
        );
        $statement->execute([
            ':uuid' => (string)$user->getId(),
            ':username' => $user->getUsername(),
            ':first_name' => $user->getName()->getFirstName(),
            ':last_name' => $user->getName()->getLastName()]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(UUID $id):User{
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$id,
        ]);
        return $this->getUser($statement, $id);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }

    // Вынесли общую логику в отдельный приватный метод

    /**
     * @throws InvalidArgumentException|UserNotFoundException
     */
    private function getUser(PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find the user by $username");
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }
        // Создаём объект пользователя с полем username
        return new User(new UUID($result['uuid']), $result['username'], new Name($result['first_name'], $result['last_name']));
    }

}