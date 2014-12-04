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
    }

    public function testEmptyFilesGetEmpty()
    {
        $preferredFile = $this->strategy->getPreferredFilesOrdered([]);
        $this->assertEmpty($preferredFile);
    }

    public function testGetSameWhenHasOneElement()
    {
        $files = [
            new File('test.zip', new Format\Zip())
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.zip', $preferredFiles[0]->getPath());
    }

    public function testGetAnyFileWhenHasTwoElements()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.phar', new Format\Phar())
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\FormatInterface', $preferredFiles[0]->getFormat());
    }


}
