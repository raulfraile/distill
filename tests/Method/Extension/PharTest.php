<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class PharTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Phar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Phar method is not available');
        }

        parent::setUp();
    }

}
