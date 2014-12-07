<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format;

use Distill\Method;

/**
 * A TAR file.
 *
 * A tar archive consists of a series of file objects. Each file object includes any file data,
 * and is preceded by a 512-byte header record. The file data is written unaltered except that
 * its length is rounded up to a multiple of 512 bytes. The end of an archive is marked by at
 * least two consecutive zero-filled records. The final block of an archive is padded out to
 * full length with zeros.
 *
 * @see http://en.wikipedia.org/wiki/Tar_(computing)
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Tar extends AbstractFormat
{
    /**
     * {@inheritdoc}
     */
    public static function getCompressionRatioLevel()
    {
        return FormatInterface::RATIO_LEVEL_LOWEST;
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtensions()
    {
        return ['tar'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getUncompressionMethods()
    {
        return [
            Method\Command\GnuTar::getName(),
            Method\Command\x7zip::getName(),
            Method\Extension\PharData::getName(),
            Method\Extension\Pear\ArchiveTar::getName(),
            Method\Native\TarExtractor::getName()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }
}
