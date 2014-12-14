<?php

namespace Distill\Tests;

use Distill\Chooser;
use Distill\Format;
use Distill\FormatGuesser;
use \Mockery as m;

class ChooserTest extends TestCase
{
    /**
     * @var Chooser
     */
    protected $chooser;

    public function setUp()
    {
        $supportChecker = m::mock('Distill\SupportCheckerInterface');
        $supportChecker->shouldReceive('isFormatSupported')->andReturn(true)->getMock();

        $this->chooser = new Chooser($supportChecker);
    }

    public function testExceptionWhenNoStrategyIsDefined()
    {
        $this->setExpectedException('Distill\\Exception\\StrategyRequiredException');

        $formatGuesser = m::mock('Distill\FormatGuesserInterface');
        $formatGuesser->shouldReceive('guess')->andReturn(new Format\Composed\TarGz(), new Format\Simple\Zip())->getMock();

        $this->chooser
            ->setFormatGuesser($formatGuesser)
            ->setFiles(['test.tgz', 'test.zip'])
            ->getPreferredFile();
    }

    public function testExceptionWhenNoFormatGuesserIsDefined()
    {
        $this->setExpectedException('Distill\\Exception\\FormatGuesserRequiredException');

        $this->chooser
            ->setFiles(['test.tgz', 'test.zip'])
            ->getPreferredFile();
    }
}
