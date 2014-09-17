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
    }

    public function testEmptyFilesGetNull()
    {
        $preferredFile = $this->strategy->getPreferredFile([]);
        $this->assertNull($preferredFile);
    }

    public function testGzShouldBePreferredOverZip()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.tgz', new Format\TarGz())
        ];

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFile->getFormat());
        $this->assertEquals('test.tgz', $preferredFile->getPath());

        array_reverse($files);

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFile->getFormat());
        $this->assertEquals('test.tgz', $preferredFile->getPath());
    }

    public function testGzShouldBePreferredOverZipEvenWhenRepeated()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.tgz', new Format\TarGz()),
            new File('test.tar.gz', new Format\TarGz()),
        ];

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFile->getFormat());

        array_reverse($files);

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFile->getFormat());
    }


}
