<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GnuTarTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\GnuTar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The GNU tar command is not installed');
        }

        parent::setUp();
    }

}
