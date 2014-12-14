<?php

namespace Distill\Tests\Format\Composed;

use Distill\Format\Composed\TarXz;
use Distill\Tests\Format\AbstractFormatTest;

class TarXzTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new TarXz();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
