<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor;

use Distill\Format\FormatInterface;

interface ExtractorInterface
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
}
