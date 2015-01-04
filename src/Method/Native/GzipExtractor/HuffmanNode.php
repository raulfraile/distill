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

class HuffmanNode
{

    /**
     * Left node
     * @var HuffmanNode
     */
    protected $leftNode;

    /**
     * Right node
     * @var HuffmanNode
     */
    protected $rightNode;

    /**
     * Node value
     * @var HuffmanNode
     */
    protected $value;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->leftNode = null;
        $this->rightNode = null;
        $this->value = null;
    }

    /**
     * Gets the left children node.
     *
     * @return HuffmanNode Left children node.
     */
    public function getLeftNode()
    {
        return $this->leftNode;
    }

    /**
     * Sets the left children node.
     * @param HuffmanNode $leftNode Left children node.
     *
     * @return HuffmanNode
     */
    public function setLeftNode(HuffmanNode $leftNode)
    {
        $this->leftNode = $leftNode;

        return $this;
    }

    /**
     * Gets the right children node.
     *
     * @return HuffmanNode Right children node.
     */
    public function getRightNode()
    {
        return $this->rightNode;
    }

    /**
     * Sets the right children node.
     * @param HuffmanNode $rightNode Right children node.
     *
     * @return HuffmanNode
     */
    public function setRightNode(HuffmanNode $rightNode)
    {
        $this->rightNode = $rightNode;

        return $this;
    }

    /**
     * Gets the value of the node.
     *
     * @return int Node value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the node.
     * @param int $value Node value.
     *
     * @return HuffmanNode
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Checks whether the node is a leaf (no children nodes) or not.
     *
     * @return bool Returns TRUE if it is a leaf, FALSE otherwise.
     */
    public function isLeaf()
    {
        return null === $this->leftNode && null === $this->rightNode;
    }
}
