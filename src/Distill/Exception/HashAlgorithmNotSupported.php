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

class HashAlgorithmNotSupportedException extends \Exception
{

    /**
     * Hash algorithm.
     * @var string
     */
    protected $algorithm;

    /**
     * Constructor.
     * @param string     $algorithm Hash algorithm.
     * @param int        $code      Exception code
     * @param \Exception $previous  Previous exception
     */
    public function __construct($algorithm, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Hash algorithm "%s" not supported. Supported algorithms: %s', $algorithm, implode(', ', hash_algos()));

        parent::__construct($message, $code, $previous);
    }

}
