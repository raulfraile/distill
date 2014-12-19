<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class CabextractTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Cabextract();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The cabextract command is not installed');
        }

        parent::setUp();
    }

}
