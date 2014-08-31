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
use Distill\Format\Zip;
use ZipArchive;

/**
 * Extracts files from zip archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class ZipAdapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extractUnzipCommand'),
                array('self', 'extractZipArchive')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof Zip
            && (class_exists('\ZipArchive') || $this->existsCommand('unzip'));
    }

    /**
     * Extracts the zip file using the unzip command.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractUnzipCommand(File $file, $path)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return false;
        }

        $command = 'unzip '.escapeshellarg($file->getPath()).' -d '.escapeshellarg($path);

        return $this->executeCommand($command);
    }

    /**
     * Extracts the zip file using the ZipArchive class.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractZipArchive(File $file, $path)
    {
        if (!class_exists('\ZipArchive')) {
            return false;
        }

        $archive = new ZipArchive;

        $archive->open($file->getPath());
        $archive->extractTo($path);
        $archive->close();

        return true;
    }

}
