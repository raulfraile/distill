<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Exception;

/**
 * Invalid argument exception.
 *
 * Exception thrown when the parameters passed to a method or function are not correct.
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * Invalid argument name.
     * @var string
     */
    protected $argument;

    /**
     * Error message.
     * @var string
     */
    protected $error;

    /**
     * Constructor
     * @param string     $argument Argument.
     * @param string     $error    Error.
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($argument, $error, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Invalid argument "%s": %s', $argument, $error);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Gets the argument.
     *
     * @return string
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * Gets the error.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
