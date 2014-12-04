<?php

namespace Distill\Tests\Method\Extension\Pear;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ArchiveTarTest extends AbstractMethodTest
{

    public function setUp()
    {
        if (!class_exists('\\ArchiveTar')) {
            $this->markTestSkipped('Archive_Tar not installed');
        }

        $this->method = new Method\Extension\Pear\ArchiveTar();
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

    public function testExtractNoTarFile()
    {
        $this->setExpectedException('Distill\\Exception\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.cab', $target, new Format\Cab());

        $this->clearTemporaryPath();
    }

}
