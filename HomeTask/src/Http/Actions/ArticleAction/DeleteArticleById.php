<?php

namespace George\HomeTask\Http\Actions\ArticleAction;

use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\HttpException;
use George\HomeTask\Http\Actions\ActionInterface;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use Psr\Log\LoggerInterface;

class DeleteArticleById implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private LoggerInterface $logger
    ) {}

    public function handle(Request $request): Response
    {
        $this->logger->info("Started deleted the article");
        try{
            $id = $request->query('id');
        }catch (HttpException $e){
            $this->logger->warning($e->getMessage(), ["error"=> $e]);
            return new ErorrResponse($e->getMessage());
        }
        $this->articlesRepository->deleteById(new UUID($id));

        return new SuccessResponse([
            "message" => "article successful deleted"
        ]);
    }
}