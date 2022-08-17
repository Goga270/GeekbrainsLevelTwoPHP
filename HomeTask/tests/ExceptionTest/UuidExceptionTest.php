<?php

use George\HomeTask\Common\UUID;
use George\HomeTask\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UuidExceptionTest extends TestCase
{
    public function testInvalidArgumentException(){
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Not good UUID: 12");

        $id = new UUID("12");
    }
}