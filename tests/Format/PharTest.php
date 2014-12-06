<?php

namespace Distill\Tests\Format;

use Distill\Format\Phar;

class PharTest extends AbstractFormatTest
{

    public function setUp()
    {
        $this->format = new Phar();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }

}
