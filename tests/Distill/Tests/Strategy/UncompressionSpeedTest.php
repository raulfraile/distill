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

    public function testEmptyFilesGetNull()
    {
        $preferredFile = $this->strategy->getPreferredFile([]);
        $this->assertNull($preferredFile);
    }

    public function testZipShouldBePreferredOverPhar()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.phar', new Format\Phar())
        ];

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFile->getFormat());
        $this->assertEquals('test.zip', $preferredFile->getPath());

        array_reverse($files);

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFile->getFormat());
        $this->assertEquals('test.zip', $preferredFile->getPath());
    }

    public function testZipShouldBePreferredOverPharEvenWhenRepeated()
    {
        $files = [
            new File('test.zip', new Format\Zip()),
            new File('test.phar', new Format\Phar()),
            new File('test.ph', new Format\Phar()),
        ];

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFile->getFormat());

        array_reverse($files);

        $preferredFile = $this->strategy->getPreferredFile($files);
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $preferredFile->getFormat());
    }


}
