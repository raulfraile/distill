<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ArTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Ar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The ar command is not installed');
        }

        parent::setUp();
    }

}
