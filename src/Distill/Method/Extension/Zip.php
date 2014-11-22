<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Extension;

use Distill\Exception\CorruptedFileException;
use Distill\Exception\FormatNotSupportedInMethodException;
use Distill\Format\FormatInterface;
use Distill\Method\AbstractMethod;

/**
 * Extracts files from zip archives using the zip extension.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Zip extends AbstractMethod
{

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            return false;
        }

        if (false === $this->isFormatSupported($format)) {
            throw new FormatNotSupportedInMethodException($this, $format);
        }

        $archive = new \ZipArchive();

        if (true !== $response = $archive->open($file)) {
            switch($response) {
                case \ZipArchive::ER_NOZIP :
                case \ZipArchive::ER_INCONS :
                case \ZipArchive::ER_CRC :
                throw new CorruptedFileException($file, CorruptedFileException::SEVERITY_HIGH);
                    break;
            }

            return false;
        }

        $archive->extractTo($target);
        $archive->close();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return extension_loaded('zip');
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
