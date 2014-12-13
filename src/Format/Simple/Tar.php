<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format\Simple;

use Distill\Format\AbstractFormat;
use Distill\Format\FormatInterface;

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
    public function getCompressionRatioLevel()
    {
        return FormatInterface::RATIO_LEVEL_LOWEST;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['tar'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }
}
