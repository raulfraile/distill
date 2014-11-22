<?php

namespace Distill\Tests\Method\Command\Xz;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class XzTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\Xz();
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
