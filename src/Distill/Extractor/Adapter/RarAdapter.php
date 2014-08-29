<?php

namespace Distill\Extractor\Adapter;

use Distill\File;
use Distill\Format\Rar;

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
     * Extracts the exe file using the unzip command.
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

}
