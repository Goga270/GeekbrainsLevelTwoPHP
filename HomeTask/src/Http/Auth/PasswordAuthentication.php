<?php

namespace George\HomeTask\Http\Auth;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Auth\Interfaces\AuthenticationInterface;
use George\HomeTask\Http\Auth\Interfaces\PasswordAuthenticationInterface;
use George\HomeTask\Http\Request;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }


    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        // 1. Идентифицируем пользователя
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
        // 2. Аутентифицируем пользователя
        // Проверяем, что предъявленный пароль
        // соответствует сохранённому в БД
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        if (!$user->checkPassword($password)) {
// Если пароли не совпадают — бросаем исключение
            throw new AuthException('Wrong password');
        }
// Пользователь аутентифицирован
        return $user;

    }
}