<?php

namespace Distill\Tests\Method\Extension\Pear;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ArchiveTarTest extends AbstractMethodTest
{
    public function setUp()
    {
        if (!class_exists('\\ArchiveTar')) {
            $this->markTestSkipped('Archive_Tar not installed');
        }

        $this->method = new Method\Extension\Pear\ArchiveTar();
        parent::setUp();
    }

}
