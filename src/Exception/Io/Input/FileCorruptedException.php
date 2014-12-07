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

/**
 * Corrupted file exception
 *
 * - SEVERITY_LOW: Even though there were errors, processing may have completed successfully.
 * - SEVERITY_HIGH: Processing probably failed immediately.
 */
class FileCorruptedException extends IOException
{
    const SEVERITY_LOW = 0;
    const SEVERITY_HIGH = 1;

    /**
     * Filename.
     * @var string
     */
    protected $filename;

    /**
     * Severity of the corrupt file.
     * @var int
     */
    protected $severity;

    /**
     * Constructor
     * @param string     $filename Filename
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($filename, $severity = self::SEVERITY_HIGH, $code = 0, \Exception $previous = null)
    {
        $this->filename = $filename;
        $this->severity = $severity;

        $message = sprintf('File "%s" is not valid', $filename);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Gets the corrupt filename.
     *
     * @return string Filename.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Gets the severity of the error.
     *
     * @return int
     */
    public function getSeverity()
    {
        return $this->severity;
    }
}
