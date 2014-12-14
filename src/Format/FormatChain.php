<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format;

class FormatChain implements FormatChainInterface, \Countable, \ArrayAccess, \IteratorAggregate
{
    /** @var FormatInterface[] $formats */
    protected $formats;

    public function __construct($formats = [])
    {
        $this->formats = $formats;
    }

    public function getChainFormats()
    {
        return $this->formats;
    }

    public function count()
    {
        return count($this->formats);
    }

    public function add(FormatInterface $format)
    {
        $this->formats[] = $format;
    }

    /**
     * Gets the compression ratio level for the whole chain.
     *
     * @return integer Compression ratio level (0: low, 10: high)
     */
    public function getCompressionRatioLevel()
    {
        // highest ratio level from all formats
        $maxLevel = FormatInterface::RATIO_LEVEL_LOWEST;

        foreach ($this->formats as $format) {
            $maxLevel = max($maxLevel, $format->getCompressionRatioLevel());
        }

        return $maxLevel;
    }

    public function offsetExists($offset)
    {
        return isset($this->formats[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->formats[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->formats[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->formats[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->formats);
    }

    public function isEmpty()
    {
        return empty($this->formats);
    }
}
