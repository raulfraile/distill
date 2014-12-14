<?php

namespace Distill\Tests\Format;

use Distill\Format\FormatInterface;
use Distill\Tests\TestCase;

abstract class AbstractFormatTest extends TestCase
{
    /** @var FormatInterface $format */
    protected $format;

    public function setUp()
    {
    }

    protected function assertLevelValid($value)
    {
        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThanOrEqual(10, $value);
    }
}
