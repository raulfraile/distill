<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Xz;
use Distill\Tests\Format\AbstractFormatTest;

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
