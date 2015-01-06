<?php

namespace Distill\Tests\Method\Native;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class GzipExtractorTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Native\GzipExtractor();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Native\\GzipExtractor method is not available');
        }

        parent::setUp();
    }

}
