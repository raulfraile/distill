<?php

namespace Distill\Tests\Format;

use Distill\Format\TarBz2;

class TarBz2Test extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new TarBz2();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

    public function testCompressionSpeedLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionSpeedLevel());
    }

    public function testUncompressionSpeedLevelIsValid()
    {
        $this->assertLevelValid($this->format->getUncompressionSpeedLevel());
    }

}
