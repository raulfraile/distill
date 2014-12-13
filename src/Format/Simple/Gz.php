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
 * A GZIP file.
 *
 * GZIP is based on the DEFLATE algorithm, which is a combination of LZ77 and Huffman coding.
 *
 * @see http://en.wikipedia.org/wiki/Gzip
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Gz extends AbstractFormat
{
    /**
     * {@inheritdoc}
     */
    public function getCompressionRatioLevel()
    {
        return FormatInterface::RATIO_LEVEL_MIDDLE;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['gz', 'gzip'];
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
            self::SAMPLE_REGULAR => self::getSampleFullPath('file.gz')
        ];
    }
}
