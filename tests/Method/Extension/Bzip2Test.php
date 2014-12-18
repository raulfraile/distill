<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class Bzip2Test extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Bzip2();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The bzip2 extension method is not available');
        }

        parent::setUp();
    }


}
