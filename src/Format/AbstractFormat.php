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

    public static function getSampleFullPath($sampleFilename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Samples' . DIRECTORY_SEPARATOR . $sampleFilename;
    }

    /**
     * {@inheritdoc}
     */
    public function isComposed()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getComposedFormats()
    {
        return [];
    }


}
