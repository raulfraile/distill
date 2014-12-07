<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill;

use Distill\Format\FormatInterface;

class File implements FileInterface
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
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return $this->format;
    }
}
