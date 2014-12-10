<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Command;

use Distill\Exception;
use Distill\Format;
use Distill\Method\MethodInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class x7zip extends AbstractCommandMethod
{
    const EXIT_CODE_OK = 0;
    const EXIT_CODE_FATAL_ERROR = 2;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $this->getFilesystem()->mkdir($target);
        $command = '7z x -y '.escapeshellarg($file).' -o'.$target;

        $exitCode = $this->executeCommand($command);

        if (self::EXIT_CODE_FATAL_ERROR === $exitCode) {
            throw new Exception\IO\Input\FileCorruptedException($file, Exception\IO\Input\FileCorruptedException::SEVERITY_HIGH);
        }

        return self::EXIT_CODE_OK === $exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $output = '';
            $this->supported = $this->existsCommand('7z');
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
        return MethodInterface::SPEED_LEVEL_HIGHEST;
    }

    /**
     * {@inheritdoc}
     */
    public function isFormatSupported(Format\FormatInterface $format)
    {
        return $format instanceof Format\Bz2
            || $format instanceof Format\Cab
            || $format instanceof Format\Gz
            || $format instanceof Format\Rar
            || $format instanceof Format\Tar
            || $format instanceof Format\x7z
            || $format instanceof Format\Xz
            || $format instanceof Format\Zip;
    }
}
