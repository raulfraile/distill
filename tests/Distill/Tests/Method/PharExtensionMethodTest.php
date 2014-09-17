<?php

namespace Distill\Tests\Method;

use Distill\Method;
use Distill\Format;

class PharExtensionMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\PharExtensionMethod();
        parent::setUp();
    }

    public function testExtractCorrectPharFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakePharFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.phar', $target, new Format\Phar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoPharFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cab', $target, new Format\Cab());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
