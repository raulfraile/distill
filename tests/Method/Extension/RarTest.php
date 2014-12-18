<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class RarTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Rar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The rar extension method is not available');
        }

        parent::setUp();
    }

}
