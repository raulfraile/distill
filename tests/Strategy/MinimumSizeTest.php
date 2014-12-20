<?php

namespace Distill\Tests\Strategy;

use Distill\File;
use Distill\Format;
use Distill\Strategy\MinimumSize;
use Distill\Tests\TestCase;

class MinimumSizeTest extends TestCase
{
    /** @var MinimumSize $strategy  */
    protected $strategy;

    public function setUp()
    {
        $this->strategy = new MinimumSize();

        parent::setUp();
    }

    public function testEmptyFilesGetEmptyArray()
    {
        $preferredFile = $this->strategy->getPreferredFilesOrdered([], []);
        $this->assertEmpty($preferredFile);
    }

    public function testGzShouldBePreferredOverZip()
    {
        $files = [
            new File('test.zip', new Format\FormatChain([new Format\Simple\Zip()])),
            new File('test.tgz', new Format\FormatChain([new Format\Composed\TarGz()]))
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\Composed\\TarGz', $preferredFiles[0]->getFormatChain()->getChainFormats()[0]);
        $this->assertEquals('test.tgz', $preferredFiles[0]->getPath());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\Composed\\TarGz', $preferredFiles[0]->getFormatChain()->getChainFormats()[0]);
        $this->assertEquals('test.tgz', $preferredFiles[0]->getPath());
    }

    public function testGzShouldBePreferredOverZipEvenWhenRepeated()
    {
        $files = [
            new File('test.zip', new Format\FormatChain([new Format\Simple\Zip()])),
            new File('test.tgz', new Format\FormatChain([new Format\Composed\TarGz()])),
            new File('test.tar.gz', new Format\FormatChain([new Format\Composed\TarGz()]))
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\Composed\\TarGz', $preferredFiles[0]->getFormatChain()->getChainFormats()[0]);

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\Composed\\TarGz', $preferredFiles[0]->getFormatChain()->getChainFormats()[0]);
    }
}
