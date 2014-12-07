<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Exception\Method;

use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;

class FormatNotSupportedInMethodException extends Exception
{
    /**
     * Method.
     * @var MethodInterface
     */
    protected $method;

    /**
     * Format.
     * @var FormatInterface
     */
    protected $format;

    /**
     * Constructor.
     * @param MethodInterface $method   Method
     * @param FormatInterface $format   Format
     * @param int             $code     Exception code
     * @param \Exception      $previous Previous exception
     */
    public function __construct(MethodInterface $method, FormatInterface $format, $code = 0, \Exception $previous = null)
    {
        $this->method = $method;
        $this->format = $format;

        $message = sprintf('Method "%s" is not supported for format %s', $method->getName(), $format->getName());

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return FormatInterface
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return MethodInterface
     */
    public function getMethod()
    {
        return $this->method;
    }
}
