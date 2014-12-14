<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class Bzip2Test extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Bzip2();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The bzip2 extension method is not available');
        }

        parent::setUp();
    }

    public function testExtractCorrectBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.bz2', $target, new Format\Simple\Bz2());

        $this->assertTrue($response);
        //$this->assertUncompressed($target, 'file_ok.gz');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeBz2File()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.bz2', $target, new Format\Simple\Bz2());

        $this->clearTemporaryPath();
    }

    public function testExtractNoBz2File()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.rar', $target, new Format\Simple\Rar());

        $this->clearTemporaryPath();
    }

}
