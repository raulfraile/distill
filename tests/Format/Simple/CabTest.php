<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Cab;
use Distill\Tests\Format\AbstractFormatTest;

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
