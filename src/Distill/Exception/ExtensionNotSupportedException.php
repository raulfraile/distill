<?php

namespace Distill\Exception;

use \Exception;


class ExtensionNotSupportedException extends Exception
{
    protected $extension;

    public function __construct($extension, $code = 0, Exception $previous = null)
    {
        $message = sprintf('Extension %s not supported', $extension);

        parent::__construct($message, $code, $previous);
    }


}