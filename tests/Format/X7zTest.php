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

}
