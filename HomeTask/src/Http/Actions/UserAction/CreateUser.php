<?php

namespace George\HomeTask\Http\Actions\UserAction;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateUser implements ActionInterface
{

    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new user");
        $id = UUID::random();
        try{
            $first_name = $request->jsonBodyField('first_name');
            $username = $request->jsonBodyField('username');
            $last_name = $request->jsonBodyField('last_name');
            $password = $request->jsonBodyField('password');
        }catch (HttpException $exception){
            $this->logger->warning($exception->getMessage(), ["error"=> $exception]);
            return new ErorrResponse($exception->getMessage());
        }

        $user = User::createFrom($username, $password, new Name($first_name, $last_name));

        $this->usersRepository->save($user);
        return new SuccessResponse([
            "message"=> "User successful created with Id= $id",
        ]);
    }
}