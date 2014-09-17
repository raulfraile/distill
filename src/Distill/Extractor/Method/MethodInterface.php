<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Method;

use Distill\Format\FormatInterface;

interface MethodInterface
{

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
}
