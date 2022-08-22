<?php
namespace George\HomeTask\UnitTests\Container;
use George\HomeTask\UnitTests\Container\SomeClassWithParametr;
use George\HomeTask\UnitTests\Container\SomeClassWitOutDepend;

class ClassDependingOnAnother
{
    // Класс с двумя зависимостями
    public function __construct(
        private SomeClassWitOutDepend $one,
        private SomeClassWithParametr $two,
    )
    {}
    }