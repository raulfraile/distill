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

use Distill\Format\FormatChainInterface;

class File implements FileInterface
{
    /**
     * File path.
     * @var string
     */
    protected $path;

    /**
     * File format.
     * @var FormatChainInterface
     */
    protected $format;

    /**
     * Constructor.
     * @param string          $path   File path
     * @param FormatChainInterface $format File format
     */
    public function __construct($path, FormatChainInterface $format)
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
    public function setFormatChain(FormatChainInterface $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatChain()
    {
        return $this->format;
    }
}
