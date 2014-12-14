<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GcabTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Cabextract();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Gnome\\Gcab command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrectCabFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cab', $target, new Format\Simple\Cab());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.cab');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeCabFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.cab', $target, new Format\Simple\Cab());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoCabFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.phar', $target, new Format\Simple\Phar());

        $this->clearTemporaryPath();
    }
}
