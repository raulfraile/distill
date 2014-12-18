<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GnuGzipTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\GnuGzip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The GNU gzip command is not installed');
        }

        parent::setUp();
    }

}
