<?php

namespace George\HomeTask\Http\Actions\CommentAction;

use George\HomeTask\Blog\Comment\Comment;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositiryInterface $commentsRepository
    ) {}

    public function handle(Request $request): Response
    {
        $id = UUID::random();
        try{
            $authorId = $request->jsonBodyField('authorId');
            $articleId = $request->jsonBodyField('articleId');
            $text = $request->jsonBodyField('text');
        }catch (HttpException $e){
            return new ErorrResponse($e->getMessage());
        }

        $this->commentsRepository->save(new Comment($id, new UUID($authorId), new UUID($articleId), $text));

        return new SuccessResponse([
            "message"=>"Comment successful created with Id=$id"
        ]);
    }
}