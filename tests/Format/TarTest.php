<?php

namespace Distill\Tests\Format;

use Distill\Format\Tar;

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
