<?php

namespace George\HomeTask\Blog\Comment;

use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\UUID;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;

class CreateCommentCommand
{
    private CommentsRepositiryInterface $usersRepository;

    public function __construct(CommentsRepositiryInterface $usersRepository) {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @throws \George\HomeTask\Exceptions\InvalidArgumentException
     * @throws \George\HomeTask\Exceptions\ArgumentsException
     */
    public function handle(Arguments $arguments, UUID $authorId, UUID $articleId ):void{
        $id = UUID::random();
        $text = $arguments->getArg('text');

        $this->usersRepository->save(new Comment($id, $authorId, $articleId, $text));
    }
}