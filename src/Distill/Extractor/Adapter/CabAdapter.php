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
use Distill\Format\Cab;

/**
 * Extracts files from Cab archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class CabAdapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extract7zCommand')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof Cab && $this->existsCommand('7z');
    }

    /**
     * Extracts the exe file using the unzip command.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extract7zCommand(File $file, $path)
    {
        if ($this->isWindows()) {
            return false;
        }

        @mkdir($path);
        $command = '7z e -y '.escapeshellarg($file->getPath()).' -o'.escapeshellarg($path);

        return $this->executeCommand($command);
    }

}
