<?php

namespace Distill\Tests;

use Distill\File;
use Distill\Format;

class FileTest extends TestCase
{

    public function testConstructorParameters()
    {
        $file = new File('test.zip', new Format\Zip());

        $this->assertEquals('test.zip', $file->getPath());
        $this->assertInstanceOf('\\Distill\\Format\\Zip', $file->getFormat());
    }

    public function testSetters()
    {
        $file = new File('test.zip', new Format\Zip());

        $file->setPath('test.tgz');
        $file->setFormat(new Format\TarGz());

        $this->assertEquals('test.tgz', $file->getPath());
        $this->assertInstanceOf('\\Distill\\Format\\TarGz', $file->getFormat());
    }

    public function testToStringReturnsPath()
    {
        $file = new File('test.zip', new Format\Zip());

        $this->assertEquals('test.zip', (string) $file);
    }

}
