<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GnuGzipTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\GnuGzip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The GNU gzip command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrectGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.gz', $target, new Format\Simple\Gz());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.gz', true);
        $this->clearTemporaryPath();
    }

    public function testExtractFakeGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.gz', $target, new Format\Simple\Gz());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoGzFile()
    {
        $this->setExpectedException('Distill\\Exception\\Method\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.cab', $target, new Format\Simple\Cab());

        $this->clearTemporaryPath();
    }
}
