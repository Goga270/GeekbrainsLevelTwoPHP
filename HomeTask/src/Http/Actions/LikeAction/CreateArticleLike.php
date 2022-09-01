<?php

namespace George\HomeTask\Http\Actions\LikeAction;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Like\ArticleLike;
use George\HomeTask\Blog\Like\Like;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\LikeExsistException;
use George\HomeTask\Exceptions\LikeNotFoundException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use George\HomeTask\Repositories\Likes\SqLiteArticleLikesRepo;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticleLike implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private SqLiteArticleLikesRepo $likesRepository,
        private LoggerInterface $logger,
        private TokenAuthenticationInterface $authentication,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new article like");
        $id = UUID::random();

        try{
            $articleId = new UUID($request->jsonBodyField('articleId'));
        }catch (HttpException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $article = $this->articlesRepository->get($articleId);
        }catch (ArticleNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse("No article created");
        }

        try{
            $user = $this->authentication->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try {
            $this->likesRepository->likeExist($article,$user);
        }catch (LikeExsistException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }



        $this->likesRepository->save(new ArticleLike(
            $id,
            $articleId,
            $user->getId()
        ));

        return new SuccessResponse([
            'message'=> "Like successfully saved $id."
        ]);
    }

    /*private function LikeExsist(Article $article, User $user):bool{
        try {
            $articleLikes = $this->likesRepository->getAllByArticle($article->getId());
        }catch (LikeNotFoundException $exception){
            return true;
        }

        foreach ($articleLikes as $like){
            if($like->getUser() == $user->getId()){
                return false;
            }
        }
        return true;
    }*/
}