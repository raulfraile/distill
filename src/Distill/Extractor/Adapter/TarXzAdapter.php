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
use Distill\Format\TarXz;

/**
 * Extracts files from tar.xz archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class TarXzAdapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extractTarCommand')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof TarXz && $this->existsCommand('tar');
    }

    /**
     * Extracts the tar.xz file using the tar command.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractTarCommand(File $file, $path)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return false;
        }

        @mkdir($path);
        $command = sprintf("tar -Jxvf %s -C %s", escapeshellarg($file->getPath()), escapeshellarg($path));

        return $this->executeCommand($command);
    }

}
