<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\X7z;
use Distill\Tests\Format\AbstractFormatTest;

class X7zTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new X7z();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
