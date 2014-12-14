<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Jar;
use Distill\Tests\Format\AbstractFormatTest;

class JarTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Jar();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
