<?php

namespace Distill\Tests\Method\Command\Xz;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class XzTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Xz();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The xz command is not installed');
        }

        parent::setUp();
    }

}
