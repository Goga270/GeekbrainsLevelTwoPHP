<?php

namespace George\HomeTask\Exceptions;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends AppException implements NotFoundExceptionInterface
{

}