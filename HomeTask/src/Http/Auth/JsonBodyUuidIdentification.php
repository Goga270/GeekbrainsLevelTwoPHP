<?php

namespace George\HomeTask\Http\Auth;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Auth\Interfaces\IdentificationInterface;
use George\HomeTask\Http\Request;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {}


    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
        // Получаем UUID пользователя из JSON-тела запроса;
        // ожидаем, что корректный UUID находится в поле user_uuid
            $userUuid = new UUID($request->jsonBodyField('authorId'));
        } catch (HttpException|InvalidArgumentException $e) {
        // Если невозможно получить UUID из запроса -
        // бросаем исключение
            throw new AuthException($e->getMessage());
        }
        try {
        // Ищем пользователя в репозитории и возвращаем его
            return $this->usersRepository->get($userUuid);
        } catch (UserNotFoundException $e) {
        // Если пользователь с таким UUID не найден -
        // бросаем исключение
            throw new AuthException($e->getMessage());
        }
    }
}