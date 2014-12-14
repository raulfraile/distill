<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class UnsharTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\Unshar();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The unshar command is not installed');
        }

        parent::setUp();
    }

    public function testExtractCorrectSharFile()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract('file_ok.shar', $target, new Format\Simple\Shar());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.shar');
        $this->clearTemporaryPath();
    }

}
