<?php

namespace George\HomeTask\Blog\User;

use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\CommandException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class CreateUserCommand
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository) {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws \George\HomeTask\Exceptions\ArgumentsException
     * @throws CommandException
     */
    public function handle(Arguments $arguments):void{
        $id = UUID::random();
        $name = new Name($arguments->getArg('first_name'), $arguments->getArg('last_name'));
        $username = $arguments->getArg('username');

        if($this->UserExist($username)){
            throw new CommandException("User already exists: $username");
        }

        $this->usersRepository->save(new User($id, $username, $name));
    }

    public function UserExist($username):bool{
        try {
            $user = $this->usersRepository->getByUsername($username);
        }catch (UserNotFoundException $e){
            return false;
        }
        return true;
    }


}