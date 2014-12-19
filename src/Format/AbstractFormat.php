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

abstract class AbstractFormat implements FormatInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        $className = static::getClass();
        $className = str_replace('\\', '', $className);
        $className = preg_replace('/^DistillFormat/', '', $className);
        $className = strtolower($className);

        return $className;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSamples()
    {
        return [];
    }

    /**
     * Gets the full path of the sample.
     * @param string $sampleFilename Sample file name.
     *
     * @return string Sample full path.
     */
    public static function getSampleFullPath($sampleFilename)
    {
        $parts = [__DIR__, '..', 'Resources', 'Samples', $sampleFilename];

        return implode(DIRECTORY_SEPARATOR, $parts);
    }
}
