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

class CreateUser implements ActionInterface
{

    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $id = UUID::random();
        try{
            $first_name = $request->jsonBodyField('first_name');
            $username = $request->jsonBodyField('username');
            $last_name = $request->jsonBodyField('last_name');
        }catch (HttpException $exception){
            return new ErorrResponse($exception->getMessage());
        }

        $this->usersRepository->save(new User($id, $username, new Name($first_name,$last_name)));
        return new SuccessResponse([
            "message"=> "User successful created with Id= $id",
        ]);
    }
}