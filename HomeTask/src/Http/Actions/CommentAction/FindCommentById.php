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

class FindCommentById implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepository
    ) {}

    public function handle(Request $request): Response
    {
        try{
            $id = $request->query('id');
        }catch (HttpException $e){
            return new ErorrResponse($e->getMessage());
        }

        try{
            $comment = $this->commentsRepository->get(new UUID($id));
        }catch (CommentNotFoundException $e){
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