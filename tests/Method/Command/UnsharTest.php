<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class UnsharTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Unshar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The unshar command is not installed');
        }

        parent::setUp();
    }

}
