<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class CpioTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Cpio();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The cpio command is not installed');
        }

        parent::setUp();
    }

}
