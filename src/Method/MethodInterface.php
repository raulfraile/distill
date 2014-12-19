<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method;

use Distill\Format\FormatInterface;

interface MethodInterface
{
    const SPEED_LEVEL_LOWEST = 0;
    const SPEED_LEVEL_LOW = 2;
    const SPEED_LEVEL_MIDDLE = 5;
    const SPEED_LEVEL_HIGH = 7;
    const SPEED_LEVEL_HIGHEST = 10;

    const STABILITY_LEVEL_LOWEST = 0;
    const STABILITY_LEVEL_LOW = 2;
    const STABILITY_LEVEL_MIDDLE = 5;
    const STABILITY_LEVEL_HIGH = 7;
    const STABILITY_LEVEL_HIGHEST = 10;

    /**
     * Extracts the compressed file into the given path.
     * @param string          $file   Compressed file
     * @param string          $path   Destination path
     * @param FormatInterface $format Format
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract($file, $path, FormatInterface $format);

    /**
     * Checks whether the method is supported in the current system.
     *
     * @return bool Returns TRUE if the method can be used.
     */
    public function isSupported();

    /**
     * Gets the method key name.
     * @static
     *
     * @return string Method name
     */
    public static function getName();

    /**
     * Gets the method class FQN.
     * @static
     *
     * @return string Method FQN.
     */
    public static function getClass();

    /**
     * Gets the uncompression speed level for the format.
     * @param FormatInterface $format Format.
     *
     * @return integer Uncompression speed level (0: low, 10: high)
     */
    public static function getUncompressionSpeedLevel(FormatInterface $format = null);

    /**
     * Gets the stability level of the method.
     *
     * It is usually related to the software being used, but sometimes it may be different
     * for each format (e.g. experimental support for some format).
     *
     * @param FormatInterface $format Format.
     *
     * @return integer Stability level (0: low, 10: high)
     */
    //public static function getStabilityLevel(FormatInterface $format);

    /**
     * Checks if the method is registered for the format.
     * @param FormatInterface $format Format.
     *
     * @return bool TRUE if it is registered, FALSE otherwise.
     */
    public function isFormatSupported(FormatInterface $format);
}
