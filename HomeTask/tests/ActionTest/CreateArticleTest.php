<?php

use George\HomeTask\Blog\Article\Article;
use George\HomeTask\Blog\Token\AuthToken;
use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\Name;
use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\ArticleNotFoundException;
use George\HomeTask\Exceptions\UserNotFoundException;
use George\HomeTask\Http\Actions\ArticleAction\CreateArticle;
use George\HomeTask\Http\Auth\BearerTokenAuthentication;
use George\HomeTask\Http\Auth\JsonBodyUuidIdentification;
use George\HomeTask\Http\ErorrResponse;
use George\HomeTask\Http\Request;
use George\HomeTask\Http\SuccessResponse;
use George\HomeTask\Repositories\Articles\ArticlesRepositoryInterface;
use George\HomeTask\Repositories\Tokens\AuthTokensRepositoryInterface;
use George\HomeTask\Repositories\Users\UsersRepositoryInterface;
use George\HomeTask\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class CreateArticleTest extends TestCase
{
    private function tokenRepository(array $tokens): AuthTokensRepositoryInterface{
        return new class($tokens) implements AuthTokensRepositoryInterface{

            public function save(\George\HomeTask\Blog\Token\AuthToken $authToken): void
            {
                // TODO: Implement save() method.
            }

            public function get(string $token): \George\HomeTask\Blog\Token\AuthToken
            {
                // TODO: Implement get() method.
            }

            public function expiredToken(string $token): void
            {
                // TODO: Implement expiredToken() method.
            }
        };
    }

    private function usersRepository(array $users): UsersRepositoryInterface
    {
// В конструктор анонимного класса передаём массив пользователей
        return new class($users) implements UsersRepositoryInterface {
            public function __construct(
                private array $users
            ) {}
            public function save(User $user): void
            {}
            public function get(UUID $uuid): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && (string)$uuid === (string)$user->getId())
                    {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->getUsername())
                    {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
        };
    }

    private function articlesRepository(array $articles): ArticlesRepositoryInterface
    {
// В конструктор анонимного класса передаём массив пользователей
        return new class($articles) implements ArticlesRepositoryInterface {
            private bool $flag=false;
            public function __construct(
                private array $articles,
            )
            {
            }


            public function save(Article $article): void
            {
                $this->flag = true;
            }

            public function get(UUID $uuid): Article
            {
                throw new ArticleNotFoundException("Not found");
            }

            public function getByTitle(string $title): Article
            {
            }

            public function getByAuthor(UUID $id): Article
            {
            }

            public function deleteById(UUID $id)
            {
            }
            public function getFlag():bool{
                return $this->flag;
            }
        };
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws JsonException
     * @throws \George\HomeTask\Exceptions\HttpException|\George\HomeTask\Exceptions\InvalidArgumentException
     */
    public function testItReturnsErrorResponseIfNotAllParamsEmpty(){
        $request = new Request([], [], '{
            "authorId": "8ddd0fbc-a047-453d-b1f3-d587933270c4",
            "title": "test_for_delete",
            "text": ""}'
        );
        $usersRepository = $this->usersRepository([new User(
            UUID::random(),
            'ivan',
            'test',
            new Name('Ivan', 'Nikitin')),]);
        $articleRepository = $this->articlesRepository([]);
        $tokenAuth = new BearerTokenAuthentication($this->tokenRepository([]), $this->usersRepository([]));
        $action = new CreateArticle($articleRepository, $tokenAuth, new DummyLogger());

        $response = $action->handle($request);
        //$this->assertInstanceOf(\George\HomeTask\Exceptions\InvalidArgumentException::class, $response);
        $this->assertInstanceOf(ErorrResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"Empty field: text"}');

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws JsonException
     * @throws \George\HomeTask\Exceptions\HttpException|\George\HomeTask\Exceptions\InvalidArgumentException
     */
    public function testItReturnsErrorResponseIfNotAllParams(){
        $request = new Request([], [], '{
            "authorId": "8ddd0fbc-a047-453d-b1f3-d587933270c4",
            "title": "test_for_delete"}'
        );
        $usersRepository = $this->usersRepository([new User(
            UUID::random(),
            'ivan',
            'test',
            new Name('Ivan', 'Nikitin')),]);
        $articleRepository = $this->articlesRepository([]);
        $tokenAuth = new BearerTokenAuthentication($this->tokenRepository([]), $usersRepository);
        $action = new CreateArticle($articleRepository, $tokenAuth, new DummyLogger());

        $response = $action->handle($request);
        //$this->assertInstanceOf(\George\HomeTask\Exceptions\InvalidArgumentException::class, $response);
        $this->assertInstanceOf(ErorrResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"No such field: text"}');

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @throws JsonException
     * @throws \George\HomeTask\Exceptions\HttpException|\George\HomeTask\Exceptions\InvalidArgumentException
     */
    public function testItReturnsSuccessfulResponse(){
        $request = new Request(['Authorization'=>'ggg'], [], '{
            "authorId": "8ddd0fbc-a047-453d-b1f3-d587933270c4",
            "title": "test_for_delete",
            "text": "Dont delete me pls"}'
        );
        $time = new DateTimeImmutable();
        $usersRepository = $this->usersRepository([new User(
            new UUID("8ddd0fbc-a047-453d-b1f3-d587933270c4"),
            'ivan',
            'test',
            new Name('Ivan', 'Nikitin')),]);
        $tokenRepository = $this->tokenRepository([new AuthToken(
            'ggg',
            new UUID("8ddd0fbc-a047-453d-b1f3-d587933270c4"),
            $time
        )]);
        $tokenAuth = new BearerTokenAuthentication($tokenRepository, $usersRepository);
        $articleRepository = $this->articlesRepository([]);
        $tokenAuth = new BearerTokenAuthentication($this->tokenRepository([]), $usersRepository);
        $action = new CreateArticle($articleRepository, $tokenAuth, new DummyLogger());

        $response = $action->handle($request);
        //$this->assertInstanceOf(\George\HomeTask\Exceptions\InvalidArgumentException::class, $response);
        $this->assertInstanceOf(SuccessResponse::class, $response);
        //$this->expectOutputString('{"success":false,"reason":"No such field: text"}');
        $this->assertTrue($articleRepository->getFlag());
        $response->send();
    }
}