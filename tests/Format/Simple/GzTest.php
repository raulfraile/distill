<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Gz;
use Distill\Tests\Format\AbstractFormatTest;

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
