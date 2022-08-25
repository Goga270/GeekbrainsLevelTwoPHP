<?php

namespace George\HomeTask\Http\Actions\LikeAction;

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Blog\Like\ArticleLike;
use George\HomeTask\Blog\Like\CommentLike;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Exceptions\LikeExsistException;
use George\HomeTask\Exceptions\LikeNotFoundException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use George\HomeTask\Repositories\Likes\SqLiteArticleLikesRepo;
use George\HomeTask\Repositories\Likes\SqLiteCommentLikesRepo;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepositiry,
        private UsersRepositoryInterface $usersRepository,
        private SqLiteCommentLikesRepo $likesRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new comment like");
        $id = UUID::random();

        try{
            $commentId = new UUID($request->jsonBodyField('commentId'));
            $userId = new UUID($request->jsonBodyField('userId'));
        }catch (HttpException|InvalidArgumentException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $comment = $this->commentsRepositiry->get($commentId);
        }catch (ArticleNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse("No comment created");
        }

        try{
            $user = $this->usersRepository->get($userId);
        }catch (UserNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse("No user created");
        }

        try {
            $this->likesRepository->likeExist($comment,$user);
        }catch (LikeExsistException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $this->likesRepository->save(new CommentLike(
            $id,
            $commentId,
            $userId
        ));

        return new SuccessResponse([
            'message'=> "Like successfully saved $id."
        ]);
    }

}