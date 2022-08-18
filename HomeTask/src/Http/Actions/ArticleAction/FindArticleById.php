<?php

namespace George\HomeTask\Http\Actions\ArticleAction;

use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class FindArticleById implements ActionInterface
{
    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository
    ) {}

    public function handle(Request $request): Response
    {
        try{
            $id = $request->query('id');
        }catch (HttpException $exception){
            return new ErorrResponse($exception->getMessage());
        }

        try{
            $article = $this->articlesRepository->get(new UUID($id));
        }catch (ArticleNotFoundException|InvalidArgumentException $e){
            return new ErorrResponse($e->getMessage());
        }

        return new SuccessResponse([
            "id"=> (string)$article->getId(),
            "authorId"=> (string)$article->getAuthorId(),
            "title"=> $article->getTitle(),
            "text"=> $article->getText()
        ]);
    }
}