<?php

namespace Distill\Tests\Format;

use Distill\Format\TarXz;

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
