<?php

namespace Distill\Tests\Format\Composed;

use Distill\Format\COmposed\TarGz;
use Distill\Tests\Format\AbstractFormatTest;

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
