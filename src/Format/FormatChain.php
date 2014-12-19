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
    /**
     * Ordered list of the formats composing the format chain.
     * @var FormatInterface[] $formats
     */
    protected $formats;

    /**
     * Constructor.
     * @param FormatInterface[] $formats
     */
    public function __construct(array $formats = [])
    {
        $this->formats = $formats;
    }

    /**
     * {@inheritdoc}
     */
    public function getChainFormats()
    {
        return $this->formats;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->formats);
    }

    /**
     * {@inheritdoc}
     */
    public function add(FormatInterface $format)
    {
        $this->formats[] = $format;
    }

    /**
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->formats[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->formats[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->formats[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->formats[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->formats);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return empty($this->formats);
    }
}
