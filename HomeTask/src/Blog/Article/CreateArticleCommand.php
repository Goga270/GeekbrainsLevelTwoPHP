<?php

namespace George\HomeTask\Blog\Article;

use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArgumentsException;
use George\HomeTask\Exceptions\CommandException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateArticleCommand
{
    private ArticlesRepositoryInterface $usersRepository;
    private LoggerInterface $logger;

    public function __construct(ArticlesRepositoryInterface $usersRepository, LoggerInterface $logger) {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     */
    public function handle(Arguments $arguments, UUID $authorId):void{
        $this->logger->info("started created new Article by command line");
        $id = UUID::random();
        $title = $arguments->getArg('title');
        $text = $arguments->getArg('text');

        $this->usersRepository->save(new Article($id, $authorId, $title,$text));
    }

}