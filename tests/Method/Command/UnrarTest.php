<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class UnrarTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Unrar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The unrar command is not installed');
        }

        parent::setUp();
    }

}
