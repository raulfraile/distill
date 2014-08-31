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
use Distill\Format\Gz;

/**
 * Extracts files from gzip archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class GzAdapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extractGzipCommand')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof Gz && $this->existsCommand('gzip');
    }

    /**
     * Extracts the gz file using the tar command.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractGzipCommand(File $file, $path)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return false;
        }

        $command = sprintf("gzip -d -c %s >> %s", escapeshellarg($file->getPath()), escapeshellarg($path));

        return $this->executeCommand($command);
    }

}
