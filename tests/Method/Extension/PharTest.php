<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class PharTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Phar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Phar method is not available');
        }

        parent::setUp();
    }

    public function testExtractCorrectPharFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.phar', $target, new Format\Simple\Phar());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.phar');
        $this->clearTemporaryPath();
    }

    public function testExtractFakePharFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.phar', $target, new Format\Simple\Phar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoPharFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.cab', $target, new Format\Simple\Cab());

        $this->clearTemporaryPath();
    }
}
