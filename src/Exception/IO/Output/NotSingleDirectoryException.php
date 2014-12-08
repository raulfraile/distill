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

class NotSingleDirectoryException extends IOException
{
    /**
     * Compressed file path.
     * @var string
     */
    protected $filename;

    /**
     * Constructor.
     * @param string     $filename Compressed file path.
     * @param int        $code     Exception code.
     * @param \Exception $previous Previous exception.
     */
    public function __construct($filename, $code = 0, \Exception $previous = null)
    {
        $this->filename = $filename;

        $message = sprintf('Compressed file "%s" does not contain a single directory', $filename);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Gets the compressed file path.
     *
     * @return string Filename.
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
