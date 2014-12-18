<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class PharDataTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\PharData();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The PharData method is not available');
        }

        parent::setUp();
    }

}
