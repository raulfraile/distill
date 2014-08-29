<?php

namespace Distill\Extractor;

use Distill\File;
use Distill\Extractor\Adapter\AdapterInterface;

interface ExtractorInterface
{

    /**
     * Extracts the compressed file into the given path.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract(File $file, $path);

    /**
     * Adds a new adapter.
     * @param AdapterInterface $adapter Adapter
     *
     * @return Extractor
     */
    public function addAdapter(AdapterInterface $adapter);

}
