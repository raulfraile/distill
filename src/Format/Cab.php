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
 * A Cabinet file.
 *
 * Cabinet (or CAB) is an archive file format for Microsoft Windows that supports lossless
 * data compression and embedded digital certificates used for maintaining archive integrity.
 * Cabinet files have .cab file name extensions and are recognized by their first 4 bytes
 * MSCF. Cabinet files were known originally as Diamond files.
 *
 * @see http://en.wikipedia.org/wiki/Cabinet_%28file_format%29
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Cab extends AbstractFormat
{

    /**
     * {@inheritdoc}
     */
    public static function getCompressionRatioLevel()
    {
        return FormatInterface::RATIO_LEVEL_MIDDLE;
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtensions()
    {
        return ['cab'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
