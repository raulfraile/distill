<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class CabextractCommandMethodTest extends AbstractMethodTest
{

    public function setUp()
    {
        $this->method = new Method\Command\CabextractCommandMethod();
        parent::setUp();
    }

    public function testExtractCorrectCabFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.cab', $target, new Format\Cab());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeCabFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_fake.cab', $target, new Format\Cab());

        $this->assertFalse($response);
        $this->clearTemporaryPath();
    }

    public function testExtractNoCabFile()
    {
        $this->setExpectedException('Distill\\Exception\\FormatNotSupportedInMethodException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->extract('file_ok.phar', $target, new Format\Phar());

        $this->clearTemporaryPath();
    }

}
