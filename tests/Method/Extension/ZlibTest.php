<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ZlibTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Zlib();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The zlib extension method is not available');
        }

        parent::setUp();
    }

}
