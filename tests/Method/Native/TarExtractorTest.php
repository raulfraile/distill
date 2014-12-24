<?php

namespace Distill\Tests\Method\Native;

use Distill\Method;
use Distill\Format;
use Distill\Tests\Method\AbstractMethodTest;

class TarExtractorTest extends AbstractMethodTest
{
    protected $validResources = [
        'file_ok', 'file_ok_no_dirs', 'file_ok_empty_file', 'file_ok_links'
    ];

    public function setUp()
    {
        $this->method = new Method\Native\TarExtractor();

        if (false === $this->method->isSupported()) {
            $this->markTestSkipped('The Native\\TarExtractor method is not available');
        }

        parent::setUp();
    }

}
