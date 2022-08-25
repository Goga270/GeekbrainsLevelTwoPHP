<?php

namespace George\HomeTask\Http\Actions\ArticleAction;

use Dotenv\Loader\Loader;
use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\Auth\JsonBodyUuidIdentification;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private JsonBodyUuidIdentification $identification,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started create new article");
        $id = UUID::random();
        try{
            $authorId = new UUID($request->jsonBodyField('authorId'));
            $title = $request->jsonBodyField('title');
            $text = $request->jsonBodyField('text');
        }catch (HttpException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $user = $this->identification->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }


        $this->articlesRepository->save(new Article($id, $authorId,$title,$text));

        return new SuccessResponse([
            "message"=> "Article successful created with Id = $id"
        ]);
    }
}