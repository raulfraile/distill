<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Rar;
use Distill\Tests\Format\AbstractFormatTest;

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
