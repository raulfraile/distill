<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class UnrarTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\Unrar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The unrar command is not installed');
        }

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
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->clearTemporaryPath();
    }

}
