<?php

namespace Distill\Tests\Method;

use Distill\Method;
use Distill\Format;

class TarCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\TarCommandMethod();
        parent::setUp();
    }

    public function testExtractCorrectTarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.tar', $target, new Format\Tar());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeTarFile()
    {
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.tar', $target, new Format\Tar());

        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.tar.bz2', $target, new Format\TarBz2());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeTarBz2File()
    {
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.tar.bz2', $target, new Format\TarBz2());

        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.tar.gz', $target, new Format\TarGz());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeTarGzFile()
    {
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.tar.gz', $target, new Format\TarGz());

        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarXzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.tar.xz', $target, new Format\TarXz());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeTarXzFile()
    {
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.tar.xz', $target, new Format\TarXz());

        $this->clearTemporaryPath();
    }

    public function testExtractNoTarFile()
    {
        $this->setExpectedException('Distill\\Exception\\CorruptedFileException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.phar', $target, new Format\Phar());
        
        $this->clearTemporaryPath();
    }

}
