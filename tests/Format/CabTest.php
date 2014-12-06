<?php

namespace Distill\Tests\Format;

use Distill\Format\Cab;

class CabTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new Cab();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

}
