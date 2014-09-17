<?php

namespace Distill\Tests;

use Distill\Format\FormatInterface;
use Symfony\Component\Finder\Finder;

use Distill\Extractor\Method\MethodInterface;

abstract class AbstractMethodTest extends TestCase
{

    /** @var MethodInterface $method */
    protected $method;



    protected function extract($file, $target, FormatInterface $format)
    {
        return $this->method->extract($this->filesPath . $file, $target, $format);
    }

}
