<?php

namespace Distill\Tests\Method\Extension;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class ZlibTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Extension\Zlib();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The zlib extension method is not available');
        }

        parent::setUp();
    }

    public function testExtractCorrectGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.gz', $target, new Format\Simple\Gz());

        $this->assertTrue($response);
        //$this->assertUncompressed($target, 'file_ok.gz');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeGzFile()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_fake.gz', $target, new Format\Simple\Gz());

        $this->clearTemporaryPath();
    }

    public function testExtractNoGzFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.rar', $target, new Format\Simple\Rar());

        $this->clearTemporaryPath();
    }

}
