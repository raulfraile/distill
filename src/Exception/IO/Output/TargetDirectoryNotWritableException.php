<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Exception\IO\Output;

use Distill\Exception\IO\Exception as IOException;

class TargetDirectoryNotWritableException extends IOException
{
    /**
     * Target directory.
     * @var string
     */
    protected $target;

    /**
     * Constructor.
     * @param string     $target   Target directory.
     * @param int        $code     Exception code.
     * @param \Exception $previous Previous exception.
     */
    public function __construct($target, $code = 0, \Exception $previous = null)
    {
        $this->target = $target;

        $message = sprintf('Target directory "%s" is not writable', $target);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Gets the target directory.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
