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
 * A RAR file.
 *
 * RAR is a proprietary archive file format that supports data compression, error
 * recovery and file spanning.
 *
 * @see http://en.wikipedia.org/wiki/RAR
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Rar extends AbstractFormat
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
        return ['rar', 'rev', 'r00', 'r01'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getUncompressionMethods()
    {
        return [
            Method\Command\Unrar::getName(),
            Method\Command\x7zip::getName(),
            Method\Extension\Rar::getName()
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
