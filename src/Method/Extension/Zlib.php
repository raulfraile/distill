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
 * Extracts files from gz archives using the zlib extension.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Zlib extends AbstractMethod
{
    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $basename = pathinfo($file, PATHINFO_FILENAME);

        if (false === $this->isValid($file)) {
            throw new Exception\IO\Input\FileCorruptedException($file, Exception\IO\Input\FileCorruptedException::SEVERITY_HIGH);
        }

        $source = gzopen($file, 'rb');

        @mkdir($target);
        $destination = fopen($target . DIRECTORY_SEPARATOR . $basename, 'w');

        $bytes = stream_copy_to_stream($source, $destination);

        return $bytes > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = extension_loaded('zlib');
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
        return MethodInterface::SPEED_LEVEL_LOW;
    }

    public function isFormatSupported(Format\FormatInterface $format)
    {
        return $format instanceof Format\Simple\Gz;
    }

    protected function isValid($file)
    {
        $fileHandler = fopen($file, 'rb');
        if (false === $fileHandler) {
            return false;
        }

        $magicNumber = bin2hex(fread($fileHandler, 2));
        fclose($fileHandler);

        return '1f8b' === $magicNumber;
    }
}
