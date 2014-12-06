<?php

namespace Distill\Tests\Format;

use Distill\Format\TarGz;

class TarGzTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new TarGz();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

}
