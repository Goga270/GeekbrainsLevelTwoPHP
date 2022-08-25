<?php

use George\HomeTask\Blog\User\CreateUserCommand;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArgumentsException;
use George\HomeTask\Exceptions\CommandException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use George\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class UserCommandClassTest extends TestCase
{
    private function getRepo()
    {
        return new class implements UsersRepositoryInterface {
            private bool $callback = false;
            public function save(User $user): void
            {
                $this->callback = true;
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getCallback(): bool
            {
                return $this->callback;
            }
        };
    }

    /**
     * @throws ArgumentsException
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     */
    public function testItSavesUserToRepository():void{

        $obj = $this->getRepo();

        $userCom = new CreateUserCommand($obj, new DummyLogger());

        $userCom->handle(new Arguments([
            'username' => 'Ivan',
            'first_name' => 'Ivan',
            'last_name' => 'Nikitin',
        ]));

        $this->assertTrue($obj->getCallback());
    }

    /**
     * @throws ArgumentsException
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws CommandException
     */
    public function testExceptionWhenUserExist():void{
        $userRepo = new class implements UsersRepositoryInterface {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                return new User(new UUID('123e4567-e89b-12d3-a456-426614174000'), 'ivan228', new Name('Ivan', 'Nikitin'));
            }

            public function getByUsername(string $username): User
            {
                return new User(new UUID('123e4567-e89b-12d3-a456-426614174000'), 'ivan228', new Name('Ivan', 'Nikitin'));
            }
        };

        $userCommand = new CreateUserCommand($userRepo, new DummyLogger());

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage("User already exists: ivan228");

        $userCommand->handle(new Arguments([
            'username' => 'ivan228',
            'first_name' => 'Ivan',
            'last_name' => 'Nikitin',
        ]));
    }

}