<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Adapter;

use Distill\File;
use Distill\Format\FormatInterface;

interface AdapterInterface
{

    /**
     * Checks whether the adapter supports the file and is available in the system.
     * @param FormatInterface $file File
     *
     * @return bool Returns TRUE if it can decompress the file.
     */
    public function supports(FormatInterface $file);

    /**
     * Extracts the compressed file into the given path.
     * @param string $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract($file, $path, FormatInterface $format);

}
