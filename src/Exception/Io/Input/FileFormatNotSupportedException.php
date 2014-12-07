<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Exception\IO\Input;

use Distill\Exception\IO\Exception as IOException;
use Distill\Format\FormatInterface;

class FileFormatNotSupportedException extends IOException
{
    /**
     * Filename.
     * @var string
     */
    protected $filename;

    /**
     * Format.
     * @var FormatInterface
     */
    protected $format;

    /**
     * Constructor
     * @param string          $filename Filename
     * @param FormatInterface $format   Format
     * @param int             $code     Exception code
     * @param \Exception      $previous Previous exception
     */
    public function __construct($filename, FormatInterface $format, $code = 0, \Exception $previous = null)
    {
        $this->filename = $filename;
        $this->format = $format;

        $message = sprintf('There are no available methods to decompress format "%s"', $format->getName());

        parent::__construct($message, $code, $previous);
    }

    /**
     * Gets the empty filename.
     *
     * @return string Filename.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return FormatInterface
     */
    public function getFormat()
    {
        return $this->format;
    }
}
