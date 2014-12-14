<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class CpioTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Cpio();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The cpio command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrectCpioFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cpio', $target, new Format\Simple\Cpio());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.cpio');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeCpioFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.cpio', $target, new Format\Simple\Cpio());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoCpioFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.phar', $target, new Format\Simple\Phar());
    }

}
