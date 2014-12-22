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
 * Format guesser required exception.
 *
 * Exception thrown when a class depends on a format guesser (e.g. Chooser) and no
 * guesser has been configured.
 */
class FormatGuesserRequiredException extends \Exception implements ExceptionInterface
{
    /**
     * Constructor
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($code = 0, \Exception $previous = null)
    {
        parent::__construct('Format guesser required', $code, $previous);
    }
}
