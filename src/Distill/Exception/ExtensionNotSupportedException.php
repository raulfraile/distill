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

class ExtensionNotSupportedException extends \Exception
{
    protected $extension;

    public function __construct($extension, $code = 0, Exception $previous = null)
    {
        $message = sprintf('Extension "%s" not supported', $extension);

        parent::__construct($message, $code, $previous);
    }

}
