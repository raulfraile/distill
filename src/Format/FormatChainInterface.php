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

interface FormatChainInterface
{
    /**
     * Gets the ordered list of chained formats.
     *
     * @return FormatInterface[]
     */
    public function getChainFormats();

    /**
     * Checks whether the chain is empty or not.
     *
     * @return boolean TRUE when is empty, FALSE otherwise.
     */
    public function isEmpty();

    /**
     * Gets the compression ratio level for the whole chain.
     *
     * @return integer Compression ratio level (0: low, 10: high)
     */
    public function getCompressionRatioLevel();

    /**
     * Gets the number of formats contained in the chain.
     *
     * @return int Number of formats.
     */
    public function count();

    /**
     * Adds a new format to the chain.
     * @param FormatInterface $format Format to be added.
     *
     * @return FormatChainInterface
     */
    public function add(FormatInterface $format);
}
