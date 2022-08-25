<?php

namespace George\HomeTask\Http\Auth;

use George\HomeTask\Blog\User\User;
use George\HomeTask\Http\Request;

interface IdentificationInterface
{
    public function user(Request $request): User;

}