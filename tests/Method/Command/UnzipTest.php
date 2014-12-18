<?php

namespace Distill\Tests\Method\Command\Unzip;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class UnzipTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Unzip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The unzip command is not installed');
        }

        parent::setUp();
    }

}
