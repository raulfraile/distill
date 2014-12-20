<?php

namespace Distill\Tests\Strategy;

use Distill\File;
use Distill\Format;
use Distill\Strategy\Random;
use Distill\Tests\TestCase;

class RandomTest extends TestCase
{
    /** @var Random $strategy  */
    protected $strategy;

    public function setUp()
    {
        $this->strategy = new Random();

        parent::setUp();
    }

    public function testEmptyFilesGetEmpty()
    {
        $preferredFile = $this->strategy->getPreferredFilesOrdered([]);
        $this->assertEmpty($preferredFile);
    }

    public function testGetSameWhenHasOneElement()
    {
        $files = [
            new File('test.zip', new Format\FormatChain([new Format\Simple\Zip()]))
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\Simple\\Zip', $preferredFiles[0]->getFormatChain()->getChainFormats()[0]);
        $this->assertEquals('test.zip', $preferredFiles[0]->getPath());
    }

    public function testGetAnyFileWhenHasTwoElements()
    {
        $files = [
            new File('test.zip', new Format\FormatChain([new Format\Simple\Zip()])),
            new File('test.phar', new Format\FormatChain([new Format\Simple\Phar()])),
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\FormatInterface', $preferredFiles[0]->getFormatChain()->getChainFormats()[0]);
    }
}
