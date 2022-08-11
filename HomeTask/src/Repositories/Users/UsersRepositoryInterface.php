<?php

namespace George\HomeTask\Repositories\Users;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Common\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
}
