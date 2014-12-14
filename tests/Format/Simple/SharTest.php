<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Shar;
use Distill\Tests\Format\AbstractFormatTest;

class SharTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Shar();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
