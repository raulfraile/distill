<?php

namespace Distill\Tests\Format;

use Distill\Format\Gz;

class GzTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new Gz();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

}
