<?php

namespace Distill\Tests\Method;

use Distill\Method;
use Distill\Format;

class PharDataMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\PharDataMethod();
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

    public function testExtractFakeBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.tar.bz2', $target, new Format\TarBz2());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoPharDataFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cab', $target, new Format\Cab());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractTarFileWithWrongFormat()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.tar', $target, new Format\Bz2());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
