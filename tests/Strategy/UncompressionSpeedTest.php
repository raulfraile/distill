<?php

namespace Distill\Tests\Strategy;

use Distill\File;
use Distill\Format;
use Distill\Method\MethodInterface;
use Distill\Strategy\UncompressionSpeed;
use Distill\Tests\TestCase;
use \Mockery as m;

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
            new File('test.zip', new Format\Simple\Zip()),
            new File('test.phar', new Format\Simple\Phar()),
        ];

        $methodMock = m::mock('Distill\Method\MethodInterface');
        $methodMock->shouldReceive('isSupported')->andReturn(true);
        $methodMock->shouldReceive('isFormatSupported')->andReturn(true);
        $methodMock->shouldReceive('getUncompressionSpeedLevel')->withAnyArgs()
            ->andReturnUsing(function ($format) {
                if ($format instanceof Format\Simple\Zip) {
                    return MethodInterface::SPEED_LEVEL_HIGHEST;
                } else {
                    return MethodInterface::SPEED_LEVEL_LOWEST;
                }
            })->getMock();

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, [$methodMock]);
        $this->assertInstanceOf('\\Distill\\Format\\Simple\\Zip', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.zip', $preferredFiles[0]->getPath());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, [$methodMock]);
        $this->assertInstanceOf('\\Distill\\Format\\Simple\\Zip', $preferredFiles[0]->getFormat());
        $this->assertEquals('test.zip', $preferredFiles[0]->getPath());
    }

    public function testZipShouldBePreferredOverPharEvenWhenRepeated()
    {
        $files = [
            new File('test.zip', new Format\Simple\Zip()),
            new File('test.phar', new Format\Simple\Phar()),
            new File('test.ph', new Format\Simple\Phar()),
        ];

        $methodMock = m::mock('Distill\Method\MethodInterface');
        $methodMock->shouldReceive('isSupported')->andReturn(true);
        $methodMock->shouldReceive('isFormatSupported')->andReturn(true);
        $methodMock->shouldReceive('getUncompressionSpeedLevel')->withAnyArgs()
            ->andReturnUsing(function ($format) {
                if ($format instanceof Format\Simple\Zip) {
                    return MethodInterface::SPEED_LEVEL_HIGHEST;
                } else {
                    return MethodInterface::SPEED_LEVEL_LOWEST;
                }
            })->getMock();

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, [$methodMock]);
        $this->assertInstanceOf('\\Distill\\Format\\Simple\\Zip', $preferredFiles[0]->getFormat());

        array_reverse($files);

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($files, [$methodMock]);
        $this->assertInstanceOf('\\Distill\\Format\\Simple\\Zip', $preferredFiles[0]->getFormat());
    }
}
