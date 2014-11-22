<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class XzCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\XzCommandMethod();
        parent::setUp();
    }

    public function testExtractCorrectXzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.xz', $target, new Format\Xz());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
    }

    public function testExtractFakeXzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.xz', $target, new Format\Xz());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoXzFile()
    {
        $this->setExpectedException('Distill\\Exception\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.zip', $target, new Format\Zip());

        $this->clearTemporaryPath();
    }

}
