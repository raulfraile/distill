<?php

namespace Distill\Tests\Method;

use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Distill\Tests\TestCase;

abstract class AbstractMethodTest extends TestCase
{

    /** @var MethodInterface $method */
    protected $method;

    protected function extract($file, $target, FormatInterface $format)
    {
        return $this->method->extract($this->filesPath . $file, $target, $format);
    }

}
