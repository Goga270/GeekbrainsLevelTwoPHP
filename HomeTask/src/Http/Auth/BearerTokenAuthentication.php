<?php

namespace George\HomeTask\Http\Auth;

use DateTimeImmutable;
use George\HomeTask\Blog\Token\AuthToken;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\AuthTokenNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use George\HomeTask\Http\Request;
use George\HomeTask\Repositories\Tokens\AuthTokensRepositoryInterface;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class BearerTokenAuthentication implements TokenAuthenticationInterface
{
    private const HEADER_PREFIX = 'Bearer ';
    public function __construct(
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository,
        // Репозиторий пользователей
        private UsersRepositoryInterface $usersRepository,
    ) {
    }


    public function user(Request $request): User
    {
        /*// Получаем HTTP-заголовок
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        // Проверяем, что заголовок имеет правильный формат
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }
        // Отрезаем префикс Bearer
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));*/

        try {
            $token = AuthToken::removeHeader($request);
        } catch (AuthException $e) {
            throw new AuthException($e->getMessage());
        }

        // Ищем токен в репозитории
        try {
            $authToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }
        // Проверяем срок годности токена
        if ($authToken->expiresOn() <= new DateTimeImmutable()) {
            throw new AuthException("Token expired: [$token]");
        }
        // Получаем UUID пользователя из токена
        $userUuid = $authToken->userUuid();
        // Ищем и возвращаем пользователя
        return $this->usersRepository->get($userUuid);

    }
}