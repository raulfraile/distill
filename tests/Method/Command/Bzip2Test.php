<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class Bzip2Test extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Bzip2();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The bzip2 command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrectBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.bz2', $target, new Format\Simple\Bz2());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.bz2', true);
        $this->clearTemporaryPath();
    }

    public function testExtractFakeBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.bz2', $target, new Format\Simple\Bz2());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoBz2File()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.cab', $target, new Format\Simple\Cab());
    }
}
