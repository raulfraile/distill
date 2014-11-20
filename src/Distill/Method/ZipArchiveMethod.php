<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method;

use Distill\Exception\CorruptedFileException;
use Distill\File;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class ZipArchiveMethod extends AbstractMethod
{

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            return false;
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
        return class_exists('\\ZipArchive');
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'zip_archive';
    }

}
