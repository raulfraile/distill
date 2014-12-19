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
    protected $formatChain;

    /**
     * Constructor.
     * @param string               $path        File path.
     * @param FormatChainInterface $formatChain Format chain.
     */
    public function __construct($path, FormatChainInterface $formatChain)
    {
        $this->path = $path;
        $this->formatChain = $formatChain;
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
    public function setFormatChain(FormatChainInterface $formatChain)
    {
        $this->formatChain = $formatChain;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatChain()
    {
        return $this->formatChain;
    }
}
