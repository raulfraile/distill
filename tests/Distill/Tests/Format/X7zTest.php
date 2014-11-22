<?php

namespace Distill\Tests\Format;

use Distill\Format\x7z;

class X7zTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new x7z();
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
