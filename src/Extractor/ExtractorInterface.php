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

use Distill\Format\FormatChainInterface;

interface ExtractorInterface
{
    /**
     * Extracts the compressed file into the given path.
     * @param string               $source   Compressed file
     * @param string               $target   Destination path
     * @param FormatChainInterface $format   Format
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract($source, $target, FormatChainInterface $format);
}
