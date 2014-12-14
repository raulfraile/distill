<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Cpio;
use Distill\Tests\Format\AbstractFormatTest;

class CpioTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Cpio();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
