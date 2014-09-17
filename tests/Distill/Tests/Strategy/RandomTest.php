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

    public function testEmptyFilesGetNull()
    {
        $preferredFile = $this->strategy->getPreferredFile([]);
        $this->assertNull($preferredFile);
    }

    public function testGetFileWhenHasOneElement()
    {
        $files = [
            new File('test.zip', new Format\Zip())
        ];

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFile->getFormat());
        $this->assertEquals('test.zip', $preferredFile->getPath());
    }

    public function testGetAnyFileWhenHasTwoElements()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.phar', new Format\Phar())
        ];

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\FormatInterface', $preferredFile->getFormat());
    }


}
