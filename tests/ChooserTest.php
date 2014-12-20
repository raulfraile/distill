<?php

namespace Distill\Tests;

use Distill\Chooser;
use Distill\File;
use Distill\Format;
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
        $supportChecker->shouldReceive('isFormatSupported')->andReturn(true);
        $supportChecker->shouldReceive('isFormatChainSupported')->andReturn(true, false)->getMock();

        $this->chooser = new Chooser($supportChecker);

        parent::setUp();
    }

    public function testExceptionWhenNoStrategyIsDefined()
    {
        $this->setExpectedException('Distill\\Exception\\StrategyRequiredException');

        $formatGuesser = m::mock('Distill\FormatGuesserInterface');
        $formatGuesser->shouldReceive('guess')->andReturn(
            new Format\FormatChain([new Format\Composed\TarGz()]),
            new Format\FormatChain([new Format\Simple\Zip()])
        )->getMock();

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

    public function testExceptionInGetPreferredFilesOrderedWhenNoStrategyIsDefined()
    {
        $this->setExpectedException('Distill\\Exception\\StrategyRequiredException');

        $this->chooser->getPreferredFilesOrdered();
    }

    public function test()
    {
        $strategy = m::mock('Distill\\Strategy\\StrategyInterface');
        $strategy->shouldReceive('getPreferredFilesOrdered')->andReturnUsing(function ($files) {
            return $files;
        })->getMock();

        $formatGuesser = m::mock('Distill\FormatGuesserInterface');
        $formatGuesser->shouldReceive('guess')->andReturn(
            new Format\FormatChain([new Format\Composed\TarGz()]),
            new Format\FormatChain([new Format\Simple\Zip()])
        )->getMock();

        $preferredFiles = $this->chooser
            ->setStrategy($strategy)
            ->setFormatGuesser($formatGuesser)
            ->setFiles(['test.tgz', 'test.zip'])
            ->getPreferredFilesOrdered();

        $this->assertCount(1, $preferredFiles);
    }
}
