<?php

namespace Distill\Tests\Format;

use Distill\Format\Xz;

class XzTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new Xz();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

}
