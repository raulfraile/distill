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
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class ArchiveTarMethod extends AbstractMethod
{

    public function extract($file, $target, FormatInterface $format)
    {
        $tar = new \Archive_Tar($file, true);

        return $tar->extract($target);
    }

    public function isSupported()
    {
        return !$this->isWindows() && class_exists('\\ArchiveTar');
    }

}
