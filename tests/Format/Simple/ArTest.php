<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Ar;
use Distill\Tests\Format\AbstractFormatTest;

class ArTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Ar();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
