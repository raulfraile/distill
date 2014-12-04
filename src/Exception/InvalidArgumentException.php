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

class InvalidArgumentException extends \InvalidArgumentException
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
     * @param string     $extension File extension
     * @param int        $code      Exception code
     * @param \Exception $previous  Previous exception
     */
    public function __construct($argument, $error, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Invalid argument "%s": %s', $argument, $error);

        parent::__construct($message, $code, $previous);
    }

}
