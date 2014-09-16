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

use Distill\Format;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class PharDataMethod extends AbstractMethod
{

    public function extract($file, $target, FormatInterface $format)
    {
        try {
            $pharFormat = $this->getPharFormat($format);
            $archive = new \PharData($file, null, null, $pharFormat);
        } catch (\UnexpectedValueException $e) {
            return false;
        }

        if (!$archive->isFileFormat($pharFormat)) {
            return false;
        }

        $archive->extractTo($target, null, true);

        return true;
    }

    protected function getPharFormat(FormatInterface $format)
    {
        if ($format instanceof Format\Tar) {
            return \Phar::TAR;
        } elseif ($format instanceof Format\TarBz2) {
            return \Phar::BZ2;
        }
    }

    public function isSupported()
    {
        return !$this->isWindows() && class_exists('\\Phar');
    }

}
