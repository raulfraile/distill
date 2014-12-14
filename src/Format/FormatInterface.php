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

interface FormatInterface
{
    const RATIO_LEVEL_LOWEST = 0;
    const RATIO_LEVEL_LOW = 2;
    const RATIO_LEVEL_MIDDLE = 5;
    const RATIO_LEVEL_HIGH = 7;
    const RATIO_LEVEL_HIGHEST = 10;

    const SAMPLE_REGULAR = 0;

    /**
     * Gets the format key name.
     * @static
     *
     * @return string Format name
     */
    public static function getName();

    /**
     * Gets the current class FQN.
     * @static
     *
     * @return string Current class FQN.
     */
    public static function getClass();

    /**
     * Gets the compression ratio level for the format.
     *
     * @return integer Compression ratio level (0: low, 10: high)
     */
    public function getCompressionRatioLevel();

    /**
     * Gets the list of extensions associated to the format.
     *
     * @return array List of extensions
     */
    public function getExtensions();

    /**
     * Gets the list of samples that methods can used to test its support.
     *
     * @return array List of samples
     */
    public static function getSamples();
}
