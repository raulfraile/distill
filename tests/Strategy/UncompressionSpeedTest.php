<?php

namespace Distill\Tests\Strategy;

use Distill\File;
use Distill\Format;
use Distill\Strategy\UncompressionSpeed;
use Distill\Tests\TestCase;

class UncompressionSpeedTest extends TestCase
{

    /** @var UncompressionSpeed $strategy  */
    protected $strategy;


    public function setUp()
    {
        $this->strategy = new UncompressionSpeed();
    }

    public function testEmptyFilesGetEmpty()
    {
        $preferredFiles = $this->strategy->getPreferredFilesOrdered([]);
        $this->assertEmpty($preferredFiles);
    }

    public function testZipShouldBePreferredOverPhar()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.phar', new Format\Phar())
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.zip', $preferredFiles[0]->getPath());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.zip', $preferredFiles[0]->getPath());
    }

    public function testZipShouldBePreferredOverPharEvenWhenRepeated()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.phar', new Format\Phar()),
            new File('test.ph', new Format\Phar()),
        ];

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFiles[0]->getFormat());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFiles[0]->getFormat());
    }


}
