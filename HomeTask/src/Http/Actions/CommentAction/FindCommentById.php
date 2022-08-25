<?php

namespace George\HomeTask\Http\Actions\CommentAction;

use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\CommentNotFoundException;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use Psr\Log\LoggerInterface;

class FindCommentById implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("Started finding a comment by Uuid");
        try{
            $id = $request->query('id');
        }catch (HttpException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        try{
            $comment = $this->commentsRepository->get(new UUID($id));
        }catch (CommentNotFoundException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }

        return new SuccessResponse([
            "id" => (string)$comment->getId(),
            "authorId"=> (string)$comment->getAuthorId(),
            "articleId"=> (string)$comment->getArticleId(),
            "text" => $comment->getText()
        ]);
    }
}