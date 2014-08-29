<?php

namespace Distill\Extractor\Adapter;

use Distill\File;
use Distill\Format\X7z;

class X7zAdapter extends AbstractAdapter
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
        return $file->getFormat() instanceof X7z && $this->existsCommand('7z');
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
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return false;
        }

        @mkdir($path);
        $command = '7z e -y '.escapeshellarg($file->getPath()).' -o'.escapeshellarg($path);

        return $this->executeCommand($command);
    }

}
