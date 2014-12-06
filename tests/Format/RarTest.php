<?php

namespace Distill\Tests\Format;

use Distill\Format\Rar;

class RarTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new Rar();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

}
