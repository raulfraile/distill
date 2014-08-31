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
use Distill\Format\Phar;

/**
 * Extracts files from phar archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
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
