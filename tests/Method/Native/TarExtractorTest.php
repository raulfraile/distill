<?php

namespace Distill\Tests\Method\Native;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class TarExtractorTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Native\TarExtractor();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Native\\TarExtractor method is not available');
        }

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
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.tar', $target, new Format\Tar());

        $this->clearTemporaryPath();
    }

}
