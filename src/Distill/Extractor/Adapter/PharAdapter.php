<?php

namespace Distill\Extractor\Adapter;

use Distill\File;
use Distill\Format\Phar;

class PharAdapter extends AbstractAdapter
{

    /**
     * Constructor.
     */
    public function __construct($methods = null)
    {
        if (null === $methods) {
            $methods = array(
                array('self', 'extractPharExtension')
            );
        }

        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(File $file)
    {
        return $file->getFormat() instanceof Phar;
    }

    /**
     * Extracts the PHAR file using the tar command.
     * @param File   $file PHAR file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function extractPharExtension(File $file, $path)
    {
        try {
            $phar = new \Phar($file->getPath());
            @mkdir($path);
            $phar->extractTo($path, null, true);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
