<?php

namespace Distill\Tests;

use Distill\Extractor\Method;
use Distill\Format;

class Bzip2CommandMethodTest extends AbstractAdapterTest
{

    public function setUp()
    {
        $this->method = new Method\Bzip2CommandMethod();
        parent::setUp();
    }

    public function testExtractCorrectBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.bz2', $target, new Format\Bz2());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
    }

    public function testExtractFakeBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.bz2', $target, new Format\Bz2());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoBz2File()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cab', $target, new Format\Cab());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

}
