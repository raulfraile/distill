<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Extension\Pear;

use Distill\Exception\FormatNotSupportedInMethodException;
use Distill\Exception\MethodNotSupportedException;
use Distill\Format;
use Distill\Method\AbstractMethod;
use Distill\Method\MethodInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class ArchiveTar extends AbstractMethod
{

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        if (!$this->isSupported()) {
            throw new MethodNotSupportedException($this);
        }

        if (false === $this->isFormatSupported($format)) {
            throw new FormatNotSupportedInMethodException($this, $format);
        }

        $tar = new \Archive_Tar($file, true);

        return $tar->extract($target);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = class_exists('\\ArchiveTar');
        }

        return $this->supported;
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
    public static function getUncompressionSpeedLevel(Format\FormatInterface $format = null)
    {
        return MethodInterface::SPEED_LEVEL_MIDDLE;
    }

    /**
     * {@inheritdoc}
     */
    public function isFormatSupported(Format\FormatInterface $format)
    {
        return $format instanceof Format\Tar
        || $format instanceof Format\TarBz2
        || $format instanceof Format\TarGz;
    }

}
