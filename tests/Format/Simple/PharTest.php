<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Phar;
use Distill\Tests\Format\AbstractFormatTest;

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
