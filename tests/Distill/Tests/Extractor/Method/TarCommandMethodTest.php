<?php

namespace Distill\Tests;

use Distill\Extractor\Method;
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
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.tar', $target, new Format\Tar());

        $this->assertFalse($response);
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
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.tar.bz2', $target, new Format\TarBz2());

        $this->assertFalse($response);
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
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.tar.gz', $target, new Format\TarGz());

        $this->assertFalse($response);
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
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.tar.xz', $target, new Format\TarXz());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoTarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
