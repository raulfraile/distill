<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class Bzip2Test extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Bzip2();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The bzip2 command is not installed');
        }

        parent::setUp();
    }

}
