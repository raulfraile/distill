<?php

namespace Distill\Tests\Method\Command\Unzip;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class UnzipCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\Unzip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The unzip command is not installed');
        }

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
        $this->setExpectedException('Distill\\Exception\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.rar', $target, new Format\Rar());

        $this->clearTemporaryPath();
    }

    public function testExtractCorruptZipFile()
    {
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_corrupt.zip', $target, new Format\Zip());
    }

    public function testExtractCorrectEpubFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.epub', $target, new Format\Epub());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/epub');
        $this->clearTemporaryPath();
    }

}
