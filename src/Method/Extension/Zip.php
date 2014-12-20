<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Extension;

use Distill\Exception;
use Distill\Format;
use Distill\Method\AbstractMethod;
use Distill\Method\MethodInterface;

/**
 * Extracts files from zip archives using the zip extension.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Zip extends AbstractMethod
{
    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $archive = new \ZipArchive();

        if (true !== $response = $archive->open($file)) {
            switch ($response) {
                case \ZipArchive::ER_NOZIP:
                case \ZipArchive::ER_INCONS:
                case \ZipArchive::ER_CRC:
                    throw new Exception\IO\Input\FileCorruptedException($file, Exception\IO\Input\FileCorruptedException::SEVERITY_HIGH);
            }

            return false;
        }

        $archive->extractTo($target);
        $archive->close();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = extension_loaded('zip');
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
        return $format instanceof Format\Simple\Zip;
    }
}
