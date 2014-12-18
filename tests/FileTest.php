<?php

namespace Distill\Tests;

use Distill\File;
use Distill\FileInterface;
use Distill\Format;

class FileTest extends TestCase
{

    /**
     * @var FileInterface
     */
    protected $file;

    public function setUp()
    {
        $formatChain = new Format\FormatChain([new Format\Simple\Zip()]);
        $this->file = new File('test.zip', $formatChain);

        parent::setUp();
    }

    public function testConstructorParameters()
    {
        $this->assertEquals('test.zip', $this->file->getPath());
        $this->assertInstanceOf('\\Distill\\Format\\Simple\\Zip', $this->file->getFormatChain()->getChainFormats()[0]);
    }

    public function testSetters()
    {
        $this->file->setPath('test.tgz');
        $this->file->setFormatChain(new Format\FormatChain([new Format\Composed\TarGz()]));

        $this->assertEquals('test.tgz', $this->file->getPath());
        $this->assertInstanceOf('\\Distill\\Format\\Composed\\TarGz', $this->file->getFormatChain()->getChainFormats()[0]);
    }

    public function testToStringReturnsPath()
    {
        $this->assertEquals('test.zip', (string) $this->file);
    }
}
