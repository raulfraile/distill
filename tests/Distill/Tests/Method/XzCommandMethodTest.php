<?php

namespace Distill\Tests\Method;

use Distill\Method;
use Distill\Format;

class XzCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\XzCommandMethod();
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
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.zip', $target, new Format\Zip());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
