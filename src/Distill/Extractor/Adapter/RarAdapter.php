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
use Distill\Format\Rar;

/**
 * Extracts files from rar archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class RarAdapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extractUnrarCommand')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof Rar && $this->existsCommand('unrar');
    }

    /**
     * Extracts the rar file using the unzip command.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractUnrarCommand(File $file, $path)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return false;
        }

        @mkdir($path);
        $command = 'unrar e '.escapeshellarg($file->getPath()).' '.escapeshellarg($path);

        return $this->executeCommand($command);
    }

    /**
     * Extracts the rar file using the Rar extension.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractRarExtension(File $file, $path)
    {
        @mkdir($path);
        $rar = \RarArchive::open($file->getPath());

        foreach ($rar->getEntries() as $entry) {
            $entry->extract($path);
        }

        $rar->close();

        return true;
    }

}
