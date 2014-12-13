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
 * A ZIP file.
 *
 * ZIP is an archive file format that supports lossless data compression, may contain one or
 * more files or folders that may have been compressed and permits a number of compression algorithms
 *
 * @see http://en.wikipedia.org/wiki/Zip_(file_format)
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Zip extends AbstractFormat
{
    /**
     * {@inheritdoc}
     */
    public function getCompressionRatioLevel()
    {
        return FormatInterface::RATIO_LEVEL_LOW;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['zip', 'zipx'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSamples()
    {
        return [
            self::SAMPLE_REGULAR => self::getSampleFullPath('file.zip')
        ];
    }
}
