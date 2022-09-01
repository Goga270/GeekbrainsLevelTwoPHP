<?php

namespace George\HomeTask\Http\Actions\Auth;

use DateTimeImmutable;
use George\HomeTask\Blog\Token\AuthToken;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\AuthTokenNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Tokens\AuthTokensRepositoryInterface;

class LogOut implements ActionInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthTokensRepositoryInterface $tokenRepository
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function handle(Request $request): Response
    {
        /*// Получаем HTTP-заголовок
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            return new ErorrResponse($e->getMessage());
        }

        // Проверяем, что заголовок имеет правильный формат
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            return new ErorrResponse("Malformed token: [$header]");
        }
        // Отрезаем префикс Bearer
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));*/
        try {
            $token = AuthToken::removeHeader($request);
        }catch (AuthException $e){
            return new ErorrResponse($e->getMessage());
        }
        // Ищем токен в репозитории
        try {
            $authToken = $this->tokenRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            return new ErorrResponse("Bad token: [$token]");
        }

        $authToken->setExpiresOn(new DateTimeImmutable());

        $this->tokenRepository->save($authToken);

        return new SuccessResponse([
            'message'=> 'Success log out!'
        ]);
    }
}