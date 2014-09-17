<?php

namespace Distill\Tests;

use Distill\Extractor\Method;
use Distill\Format;

class X7zCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\X7zCommandMethod();
        parent::setUp();
    }

    public function testExtractCorrect7zFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.7z', $target, new Format\X7z());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
    }

    public function testExtractFake7zFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.7z', $target, new Format\X7z());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNo7zFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
