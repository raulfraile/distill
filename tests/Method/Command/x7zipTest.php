<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class x7zipTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\x7zip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The 7zip command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrect7zFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.7z', $target, new Format\x7z());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.7z');
        $this->clearTemporaryPath();
    }

    public function testExtractFake7zFile()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.7z', $target, new Format\x7z());

        $this->clearTemporaryPath();
    }

    public function testExtractNo7zFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractCorruptZipFile()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_corrupt.zip', $target, new Format\Zip());
    }

    public function testExtractCorrectJarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.jar', $target, new Format\Jar());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.jar');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectRarFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.rar', $target, new Format\Rar());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.rar');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectDmgFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.dmg', $target, new Format\Dmg());

        $this->assertTrue($response);
        //$this->assertUncompressed($target, 'file_ok.dmg');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectIsoFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.iso', $target, new Format\Iso());

        $this->assertTrue($response);
        //$this->assertUncompressed($target, 'file_ok.dmg');
        $this->clearTemporaryPath();
    }
}
