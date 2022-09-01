<?php

namespace George\HomeTask\Http\Actions\Auth;

use DateTimeImmutable;
use Exception;
use George\HomeTask\Blog\Token\AuthToken;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\Auth\Interfaces\AuthenticationInterface;
use George\HomeTask\Http\Auth\Interfaces\PasswordAuthenticationInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Tokens\AuthTokensRepositoryInterface;

class LogIn implements ActionInterface
{
    public function __construct(
        // Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }


    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        // Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErorrResponse($e->getMessage());
        }
        // Генерируем токен
        $authToken = new AuthToken(
        // Случайная строка длиной 40 символов
            bin2hex(random_bytes(40)),
            $user->getId(),
        // Срок годности - 1 день
            (new DateTimeImmutable())->modify('+1 day')
        );
        // Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
        // Возвращаем токен
        return new SuccessResponse([
            'token' => (string)$authToken->token(),
        ]);
    }
}