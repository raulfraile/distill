<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ArTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Ar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The ar command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrectArFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.ar', $target, new Format\Simple\Ar());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.ar');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeArFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.ar', $target, new Format\Simple\Ar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoArFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.cab', $target, new Format\Simple\Cab());
    }

    public function testExtractCorrectDebFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.deb', $target, new Format\Simple\Deb());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.deb');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeDebFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.deb', $target, new Format\Simple\Deb());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }
}
