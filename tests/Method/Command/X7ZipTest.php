<?php

namespace Distill\Tests\Method\Command;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class X7ZipTest extends AbstractMethodTest
{
    public function setUp()
    {
        $this->method = new Method\Command\X7Zip();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The 7zip command is not installed');
        }

        parent::setUp();
    }

    public function test7zFormatIn7zipMethod()
    {
        $this->checkFormatUsingMethod(new Format\Simple\X7z(), $this->method);
    }

}
