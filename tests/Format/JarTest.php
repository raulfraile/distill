<?php

namespace Distill\Tests\Format;

use Distill\Format\Jar;

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
