<?php

namespace George\HomeTask\Http\Actions;

use George\HomeTask\Http\Request;
use George\HomeTask\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;

}