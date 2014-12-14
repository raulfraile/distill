<?php

namespace Distill\Tests\Format\Simple;

use Distill\Format\Simple\Zip;
use Distill\Tests\Format\AbstractFormatTest;

class ZipTest extends AbstractFormatTest
{
    public function setUp()
    {
        $this->format = new Zip();
    }

    public function testCompressionRatioLevelIsValid()
    {
        $this->assertLevelValid($this->format->getCompressionRatioLevel());
    }
}
