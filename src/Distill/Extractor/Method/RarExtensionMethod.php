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
class RarExtensionMethod extends AbstractMethod
{

    public function extract($file, $target, FormatInterface $format)
    {
        $rar = @\RarArchive::open($file);

        if (false === $rar) {
            return false;
        }

        @mkdir($target);

        foreach ($rar->getEntries() as $entry) {
            $entry->extract($target);
        }

        $rar->close();

        return true;
    }

    public function isSupported()
    {
        return !$this->isWindows() && class_exists('\\RarArchive');
    }

}
