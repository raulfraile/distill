<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Tar;
use Distill\Tests\Format\AbstractFormatTest;

class TarTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Tar();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
