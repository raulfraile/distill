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
 * Strategy required exception.
 *
 * Exception thrown when a class depends on a strategy (e.g. Chooser) and no
 * strategy has been configured.
 */
class StrategyRequiredException extends \Exception implements ExceptionInterface
{
    /**
     * Constructor.
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($code = 0, \Exception $previous = null)
    {
        parent::__construct('Strategy required', $code, $previous);
    }
}
