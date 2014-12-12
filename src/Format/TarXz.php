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

class TarXz extends AbstractFormat
{
    /**
     * {@inheritdoc}
     */
    public static function getCompressionRatioLevel()
    {
        return FormatInterface::RATIO_LEVEL_HIGHEST;
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtensions()
    {
        return ['tar.xz', 'txz'];
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
    public function isComposed()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getComposedFormats()
    {
        return [
            Xz::getClass(),
            Tar::getClass(),
        ];
    }
}
