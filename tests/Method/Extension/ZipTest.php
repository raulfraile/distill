<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ZipTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Zip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The zip extension method is not available');
        }

        parent::setUp();
    }

}
