<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ZipMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Extension\Zip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The zip extension method is not available');
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
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.zip', $target, new Format\Zip());

        $this->clearTemporaryPath();
    }

    public function testExtractNoZipFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.rar', $target, new Format\Rar());

        $this->clearTemporaryPath();
    }

    public function testExtractCorruptZipFile()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

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
