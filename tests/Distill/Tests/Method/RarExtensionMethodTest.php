<?php

namespace Distill\Tests\Method;

use Distill\Method;
use Distill\Format;

class RarExtensionMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\RarExtensionMethod();
        parent::setUp();
    }

    public function testExtractCorrectRarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.rar', $target, new Format\Rar());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeRarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.rar', $target, new Format\Rar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoRarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
