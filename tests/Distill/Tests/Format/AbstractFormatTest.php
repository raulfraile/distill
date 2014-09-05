<?php

namespace Distill\Tests\Format;

use Distill\Format\FormatInterface;
use Symfony\Component\Finder\Finder;

abstract class AbstractFormatTest extends \PHPUnit_Framework_TestCase
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
