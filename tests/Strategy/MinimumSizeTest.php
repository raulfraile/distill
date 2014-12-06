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

    public function testEmptyFilesGetEmptyArray()
    {
        $preferredFile = $this->strategy->getPreferredFilesOrdered([], []);
        $this->assertEmpty($preferredFile);
    }

    public function testGzShouldBePreferredOverZip()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.tgz', new Format\TarGz())
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.tgz', $preferredFiles[0]->getPath());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.tgz', $preferredFiles[0]->getPath());
    }

    public function testGzShouldBePreferredOverZipEvenWhenRepeated()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.tgz', new Format\TarGz()),
            new File('test.tar.gz', new Format\TarGz()),
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFiles[0]->getFormat());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, []);
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $preferredFiles[0]->getFormat());
    }


}
