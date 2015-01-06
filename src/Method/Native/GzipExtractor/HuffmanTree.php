<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Native\GzipExtractor;

class HuffmanTree
{
    /**
     * Length of the longest code.
     * @var int
     */
    protected $maxLength;

    /**
     * Length of the shortest code.
     * @var int
     */
    protected $minLength;

    /**
     * Values.
     * @var array
     */
    protected $values;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->minLength = null;
        $this->maxLength = null;
    }

    /**
     * Builds the Huffman tree from code lengths.
     * @param array $lengths Code lengths.
     *
     * @static
     *
     * @return HuffmanTree
     */
    public static function createFromLengths(array $lengths)
    {
        ksort($lengths);

        // step 1
        $maxBitLength = max($lengths);
        $minBitLength = $maxBitLength;
        $blCount = array_combine(range(1, $maxBitLength), array_fill(0, $maxBitLength, 0));
        foreach ($lengths as $value) {
            if (false === array_key_exists($value, $blCount)) {
                $blCount[$value] = 0;
            }

            if ($value < $minBitLength && $value > 0) {
                $minBitLength = $value;
            }

            $blCount[$value]++;
        }

        // step 2
        $code = 0;
        $nextCode = [];
        $blCount[0] = 0;
        for ($bits = 1; $bits <= $maxBitLength; $bits++) {
            $code = ($code + $blCount[$bits-1]) << 1;
            $nextCode[$bits] = $code;
        }

        // step 3
        $i = 0;
        $codes = [];
        foreach ($lengths as $key => $length) {
            if ($length != 0) {
                $codes[$key] = str_pad(decbin($nextCode[$length]), $length, '0', STR_PAD_LEFT);
                $nextCode[$length]++;

                $i++;
            }
        }

        // generate the tree
        $tree = new HuffmanTree();

        $tree
            ->setValues(array_combine(array_values($codes), array_keys($codes)))
            ->setMinLength($minBitLength)
            ->setMaxLength($maxBitLength);

        return $tree;
    }

    /**
     * Decodes a value.
     * @param string $value Value to be decoded.
     *
     * @return bool|int If it exists, decoded value, FALSE otherwise.
     */
    public function decode($value)
    {
        if (false === array_key_exists($value, $this->values)) {
            return false;
        }

        return $this->values[$value];
    }

    /**
     * Finds the next symbol that can be decoded by the tree and returns its value.
     * @param BitReader $bitReader Bit reader.
     *
     * @return bool|int The decoded value, FALSE if no value could be decoded.
     */
    public function findNextSymbol(BitReader $bitReader)
    {
        // initialize with minimum bits
        $bits = strrev($bitReader->readBitStream($this->minLength));
        $bitsLength = $this->minLength;

        $symbol = $this->decode($bits);

        while (false === $symbol && $bitsLength < $this->maxLength) {
            $bits .= $bitReader->readBitStream(1);
            $symbol = $this->decode($bits);

            $bitsLength++;
        }

        return $symbol;
    }

    /**
     * Gets the length of the longest code.
     *
     * @return int Max length.
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * Sets the length of longest code.
     * @param int $maxLength Max length.
     *
     * @return HuffmanTree
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * Gets the length of the shortest code.
     *
     * @return int Min length.
     */
    public function getMinLength()
    {
        return $this->minLength;
    }

    /**
     * Sets the length of the shortest code.
     * @param int $minLength Min length.
     *
     * @return HuffmanTree
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;

        return $this;
    }

    /**
     * Gets the values.
     *
     * @return array Values.
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Sets the values.
     * @param array $values Values.
     *
     * @return HuffmanTree
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }
}
