<?php

namespace George\HomeTask\Http\Auth;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Http\Auth\Interfaces\IdentificationInterface;
use George\HomeTask\Http\Request;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class JsonBodyUsernameIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {}

    public function user(Request $request): User
    {

    }
}