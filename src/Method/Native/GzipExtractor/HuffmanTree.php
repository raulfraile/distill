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
    /** @var HuffmanNode $rootNode */
    protected $rootNode;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rootNode = null;
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

        $codes = [];

        // step 1
        $maxBitLength = max($lengths);
        $bl_count = array_combine(range(1, $maxBitLength), array_fill(0, $maxBitLength, 0));
        foreach ($lengths as $value) {
            if (false === array_key_exists($value, $bl_count)) {
                $bl_count[$value] = 0;
            }

            $bl_count[$value]++;
        }

        // step 2
        $code = 0;
        $nextCode = [];
        $bl_count[0] = 0;
        for ($bits = 1; $bits <= $maxBitLength; $bits++) {
            $code = ($code + $bl_count[$bits-1]) << 1;
            $nextCode[$bits] = $code;
        }

        // step 3
        $i = 0;
        foreach ($lengths as $key => $length) {
            if ($length != 0) {
                $codes[$key] = str_pad(decbin($nextCode[$length]), $length, '0', STR_PAD_LEFT);
                $nextCode[$length]++;

                $i++;
            }
        }

        // generate the tree
        asort($codes);
        $tree = new HuffmanTree();
        $tree->setRootNode(new HuffmanNode());

        foreach ($codes as $key => $code) {
            $currentNode = $tree->getRootNode();

            for ($i = 0; $i < strlen($code); $i++) {
                if ('0' === $code[$i]) {
                    if (null === $currentNode->getLeftNode()) {
                        $node = new HuffmanNode();

                        if (($i+1) >= strlen($code)) {
                            $node->setValue($key);
                        }

                        $currentNode->setLeftNode($node);
                    }

                    $currentNode = $currentNode->getLeftNode();
                } else {
                    if (null === $currentNode->getRightNode()) {
                        $node = new HuffmanNode();

                        if (($i+1) >= strlen($code)) {
                            $node->setValue($key);
                        }

                        $currentNode->setRightNode($node);
                    }

                    $currentNode = $currentNode->getRightNode();
                }
            }
        }

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
        if (empty($value)) {
            return false;
        }

        $currentNode = $this->rootNode;

        $i = 0;
        while ($i < strlen($value)) {
            $currentValue = $value[$i];

            if ($currentNode->isLeaf()) {
                return false;
            }

            if ('0' === $currentValue) {
                $currentNode = $currentNode->getLeftNode();
            } else {
                $currentNode = $currentNode->getRightNode();
            }

            $i++;
        }

        if (false === $currentNode->isLeaf()) {
            return false;
        }

        return $currentNode->getValue();
    }

    public function findNextSymbol(BitReader $bitReader)
    {
        $symbol = false;
        $bits = '';
        while (false === $symbol) {
            $bits .= decbin($bitReader->read(1));
            $symbol = $this->decode($bits);
        }

        return $symbol;
    }

    /**
     * Gets the root node.
     *
     * @return HuffmanNode Root node
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }

    /**
     * Sets the root node.
     * @param HuffmanNode $rootNode Root node.
     *
     * @return HuffmanTree
     */
    public function setRootNode(HuffmanNode $rootNode)
    {
        $this->rootNode = $rootNode;

        return $this;
    }
}
