<?php

namespace George\HomeTask\Blog\Comment;

use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArgumentsException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Repositories\Comments\CommentsRepositiryInterface;
use Psr\Log\LoggerInterface;

class CreateCommentCommand
{
    private CommentsRepositiryInterface $usersRepository;
    private LoggerInterface $logger;

    public function __construct(CommentsRepositiryInterface $usersRepository, LoggerInterface $logger) {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     */
    public function handle(Arguments $arguments, UUID $authorId, UUID $articleId ):void{
        $this->logger->info("Started created new comment by command line");
        $id = UUID::random();
        $text = $arguments->getArg('text');

        $this->usersRepository->save(new Comment($id, $authorId, $articleId, $text));
    }
}