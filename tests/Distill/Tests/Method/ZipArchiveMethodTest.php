<?php

namespace Distill\Tests\Method;

use Distill\Method;
use Distill\Format;

class ZipArchiveMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\ZipArchiveMethod();
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
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.zip', $target, new Format\Zip());

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

}
