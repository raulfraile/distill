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

use Distill\Method\MethodInterface;

class MethodNotSupportedException extends Exception
{
    /**
     * Method.
     * @var MethodInterface
     */
    protected $method;

    /**
     * Constructor.
     * @param MethodInterface $method   Method
     * @param int             $code     Exception code
     * @param \Exception      $previous Previous exception
     */
    public function __construct(MethodInterface $method, $code = 0, \Exception $previous = null)
    {
        $this->method = $method;

        $message = sprintf('Method "%s" is not supported', $method->getName());

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return MethodInterface
     */
    public function getMethod()
    {
        return $this->method;
    }
}
