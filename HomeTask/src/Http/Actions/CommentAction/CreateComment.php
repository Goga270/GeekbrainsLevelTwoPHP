<?php

namespace George\HomeTask\Http\Actions\CommentAction;

use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\AuthException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\Auth\Interfaces\TokenAuthenticationInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepository,
        private LoggerInterface $logger,
        private TokenAuthenticationInterface $authentication
    ) {}

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     */
    public function handle(Request $request): Response
    {
        $this->logger->info("Started created new comment");
        $id = UUID::random();
        try{
            $articleId = $request->jsonBodyField('articleId');
            $text = $request->jsonBodyField('text');
        }catch (HttpException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $user = $this->authentication->user($request);
        }catch (AuthException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        $this->commentsRepository->save(new Comment($id, $user->getId(), new UUID($articleId), $text));

        return new SuccessResponse([
            "message"=>"Comment successful created with Id=$id"
        ]);
    }
}