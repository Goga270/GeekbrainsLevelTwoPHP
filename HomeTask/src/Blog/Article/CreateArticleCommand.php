<?php

namespace George\HomeTask\Blog\Article;

use George\HomeTask\Common\Arguments;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArgumentsException;
use George\HomeTask\Exceptions\CommandException;
use George\HomeTask\Exceptions\InvalidArgumentException;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;

class CreateArticleCommand
{
    private ArticlesRepositoryInterface $usersRepository;

    public function __construct(ArticlesRepositoryInterface $usersRepository) {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @throws InvalidArgumentException
     * @throws ArgumentsException
     */
    public function handle(Arguments $arguments, UUID $authorId):void{
        $id = UUID::random();
        $title = $arguments->getArg('title');
        $text = $arguments->getArg('text');

        $this->usersRepository->save(new Article($id, $authorId, $title,$text));
    }

}