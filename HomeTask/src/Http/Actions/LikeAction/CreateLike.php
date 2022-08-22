<?php

namespace George\HomeTask\Http\Actions\LikeAction;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Like\Like;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\LikeNotFoundException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Likes\LikesRepositoryInterface;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;

class CreateLike implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private ?UsersRepositoryInterface $usersRepository,
        private LikesRepositoryInterface $likesRepository
    ) {}

    public function handle(Request $request): Response
    {
        $id = UUID::random();

        try{
            $articleId = new UUID($request->jsonBodyField('articleId'));
            $userId = new UUID($request->jsonBodyField('userId'));
        }catch (HttpException|InvalidArgumentException $e){
            return new ErorrResponse($e->getMessage());
        }

        try{
            $article = $this->articlesRepository->get($articleId);
        }catch (ArticleNotFoundException $exception){
            return new ErorrResponse("No article created");
        }

        try{
            $user = $this->usersRepository->get($userId);
        }catch (UserNotFoundException $exception){
            return new ErorrResponse("No user created");
        }

        /*$articleLikes = $this->likesRepository->getAllByArticle($articleId);
        print_r($articleLikes);*/

        if(!$this->LikeExsist($article, $user)){
            return new ErorrResponse("Post already like by you");
        }

        $this->likesRepository->save(new Like(
            $id,
            $articleId,
            $userId
        ));

        return new SuccessResponse([
            'message'=> "Like successfully saved $id."
        ]);
    }

    private function LikeExsist(Article $article, User $user):bool{
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
    }
}