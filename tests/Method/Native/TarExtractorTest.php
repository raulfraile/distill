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

}
