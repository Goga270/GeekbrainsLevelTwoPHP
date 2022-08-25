<?php

namespace George\HomeTask\Http\Actions\LikeAction;

use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\LikeNotFoundException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use George\HomeTask\Http\ErorrResponse;
use Psr\Log\LoggerInterface;

class FindLikesByArticle implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
        private ArticlesRepositoryInterface $articlesRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("Started finding Likes by an article");
        $message = [];
        try{
            $id = $request->query('id');
        }catch (HttpException $exception){
            $this->logger->warning($exception->getMessage(), ["error"=> $exception]);
            return new ErorrResponse($exception->getMessage());
        }

        try{
            $article = $this->articlesRepository->get(new UUID($id));
        }catch (ArticleNotFoundException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $articleLikes = $this->likesRepository->getAllByArticle(new UUID($id));
        }catch (LikeNotFoundException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $i=0;
        foreach ($articleLikes as $like){
            $message[$i] = [
                "id"=>(string)$like->getLike(),
                "article"=>(string)$like->getArticle(),
                "user"=> (string)$like->getUser()
            ];
            $i++;
        }
        return new SuccessResponse($message);
    }
}