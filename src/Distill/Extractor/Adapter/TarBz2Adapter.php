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
use Distill\Format\TarBz2;

class TarBz2Adapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extractTarCommand'),
                array('self', 'extractArchiveTar')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof TarBz2 &&
        (extension_loaded('Archive_Tar') || $this->existsCommand('tar'));
    }

    /**
     * Extracts the tar.gz file using the tar command.
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
        $command = sprintf("tar -jxvf %s -C %s", escapeshellarg($file->getPath()), escapeshellarg($path));

        return $this->executeCommand($command);
    }

    /**
     * Extracts the tar.gz file using the Archive_Tar extension.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractArchiveTar(File $file, $path)
    {
        if (!extension_loaded('Archive_Tar')) {
            return false;
        }

        $tar = new \Archive_Tar($file->getPath(), true);

        return $tar->extract($path);
    }

}
