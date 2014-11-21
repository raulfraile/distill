<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GzipCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\GzipCommandMethod();
        parent::setUp();
    }

    public function testExtractCorrectGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.gz', $target, new Format\Gz());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
    }

    public function testExtractFakeGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.gz', $target, new Format\Gz());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoGzFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cab', $target, new Format\Cab());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
