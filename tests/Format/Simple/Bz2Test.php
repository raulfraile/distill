<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Bz2;
use Distill\Tests\Format\AbstractFormatTest;

class Bz2Test extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Bz2();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
