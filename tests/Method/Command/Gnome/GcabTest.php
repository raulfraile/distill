<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GcabTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Gnome\Gcab();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Gnome\\Gcab command is not installed');
        }

        parent::setUp();
    }

}
