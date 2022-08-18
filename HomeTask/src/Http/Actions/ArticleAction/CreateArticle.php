<?php

namespace George\HomeTask\Http\Actions\ArticleAction;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private ?UsersRepositoryInterface $usersRepository = null
    ) {}

    public function handle(Request $request): Response
    {
        $id = UUID::random();
        try{
            $authorId = new UUID($request->jsonBodyField('authorId'));
            $title = $request->jsonBodyField('title');
            $text = $request->jsonBodyField('text');
        }catch (HttpException|InvalidArgumentException $e){
            return new ErorrResponse($e->getMessage());
        }

        try{
            $this->usersRepository->get($authorId);
        }catch (UserNotFoundException $e){
            return new ErorrResponse($e->getMessage());
        }


        $this->articlesRepository->save(new Article($id, $authorId,$title,$text));

        return new SuccessResponse([
            "message"=> "Article successful created with Id = $id"
        ]);
    }
}