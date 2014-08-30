<?php

namespace Distill;

use Distill\Format\FormatInterface;

class File
{

    /**
     * File path.
     * @var string
     */
    protected $path;

    /**
     * File format.
     * @var FormatInterface
     */
    protected $format;

    /**
     * Constructor.
     * @param string          $path   File path
     * @param FormatInterface $format File format
     */
    public function __construct($path, FormatInterface $format)
    {
        $this->path = $path;
        $this->format = $format;
    }

    /**
     * Sets the file path.
     * @param string $path File path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Gets the file path.
     *
     * @return string File path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the file format.
     * @param FormatInterface $format File format
     *
     * @return File
     */
    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Gets the file format.
     *
     * @return FormatInterface File format
     */
    public function getFormat()
    {
        return $this->format;
    }


}
