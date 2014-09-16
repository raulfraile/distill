<?php

namespace Distill\Tests;

use Distill\Extractor\Method;
use Distill\Format;

class UnzipCommandMethodTest extends AbstractAdapterTest
{

    public function setUp()
    {
        $this->method = new Method\UnzipCommandMethod();
        parent::setUp();
    }

    public function testExtractCorrectZipFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.zip', $target, new Format\Zip());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeZipFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.zip', $target, new Format\Zip());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoZipFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.rar', $target, new Format\Rar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
