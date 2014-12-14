<?php

namespace Distill\Tests\Format\Composed;

use Distill\Format\Composed\TarBz2;
use Distill\Tests\Format\AbstractFormatTest;

class TarBz2Test extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new TarBz2();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
