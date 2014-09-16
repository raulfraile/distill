<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Method;

use Distill\File;
use Distill\Format\Bz2;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class PharExtensionMethod extends AbstractMethod
{

    public function extract($file, $target, FormatInterface $format)
    {
        try {
            $phar = new \Phar($file);
            @mkdir($target);
            $phar->extractTo($target, null, true);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function isSupported()
    {
        return !$this->isWindows() && class_exists('\\Phar');
    }

}
