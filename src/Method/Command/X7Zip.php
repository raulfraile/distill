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
use Distill\Method\AbstractMethod;
use Distill\Method\MethodInterface;

/**
 * Extracts compressed files using the 7zip/p7zip tool.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class X7Zip extends AbstractCommandMethod
{
    const EXIT_CODE_OK = 0;
    const EXIT_CODE_WARNING = 1;
    const EXIT_CODE_FATAL_ERROR = 2;
    const EXIT_CODE_BAD_COMMAND_LINE_ARGUMENTS = 7;
    const EXIT_CODE_NOT_ENOUGH_MEMORY = 8;
    const EXIT_CODE_PROCESS_STOPPED = 255;

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
        // Windows systems:
        //  - Supports:
        //     - Packing / unpacking: 7z, xz, bz2, gz, tar, zip and wim.
        //     - Unpacking only: arj, cab, chm, cpio, CramFS, deb, dmg, fat, hfs, iso, lzh, lzma, mbr, msi, nsis, ntfs, rar, rpm, SquashFS, udf, vhd, wim, xar and z.
        // In Unix systems, a port of 7-Zip is used: p7zip, and is divided in 3 packages:
        //  - p7zip: Only provides support for 7z files
        //  - p7zip-full: Provides support for many other formats (rar not included)
        //  - p7zip-rar: Provides support for rar files

        if ($format instanceof Format\Simple\X7z) {
            return true;
        }

        $osType = $this->getOsType();

        if ($this->couldBeSupported($format)) {
            if (AbstractMethod::OS_TYPE_WINDOWS === $osType) {
                return true;
            }

            if (AbstractMethod::OS_TYPE_UNIX === $osType || AbstractMethod::OS_TYPE_DARWIN === $osType) {
                if ($format instanceof Format\Simple\Rar) {
                    return $this->checkFormatSupport(new Format\Simple\Rar());
                }

                return $this->checkFormatSupport(new Format\Simple\Zip());
            }
        }

        return false;
    }

    /**
     * Checks whether the format could be supported by 7zip. Further checks should
     * be perform if the format could be supported.
     * @param Format\FormatInterface $format Format.
     *
     * @return bool Returns TRUE if the format could be supported, FALSE otherwise.
     */
    protected function couldBeSupported(Format\FormatInterface $format)
    {
        return $format instanceof Format\Simple\Arj ||
            $format instanceof Format\Simple\Bz2    ||
            $format instanceof Format\Simple\Cab    ||
            $format instanceof Format\Simple\Chm    ||
            $format instanceof Format\Simple\Cpio   ||
            $format instanceof Format\Simple\Deb    ||
            $format instanceof Format\Simple\Dmg    ||
            $format instanceof Format\Simple\Gz     ||
            $format instanceof Format\Simple\Iso    ||
            $format instanceof Format\Simple\Lzh    ||
            $format instanceof Format\Simple\Lzma   ||
            $format instanceof Format\Simple\Msi    ||
            $format instanceof Format\Simple\Rar    ||
            $format instanceof Format\Simple\Rpm    ||
            $format instanceof Format\Simple\Tar    ||
            $format instanceof Format\Simple\Wim    ||
            $format instanceof Format\Simple\Xz     ||
            $format instanceof Format\Simple\Zip;
    }

    /**
     * Checks the support for a format.
     * @param Format\FormatInterface $format Format
     *
     * @return bool Returns TRUE if the format is supported, FALSE otherwise.
     */
    protected function checkFormatSupport(Format\FormatInterface $format)
    {
        $samples = $format->getSamples();

        if (false === array_key_exists(Format\FormatInterface::SAMPLE_REGULAR, $samples)) {
            return false;
        }

        if (false === file_exists($samples[Format\FormatInterface::SAMPLE_REGULAR])) {
            return false;
        }

        $exitCode = $this->executeCommand('7z t '.$samples[Format\FormatInterface::SAMPLE_REGULAR]);

        return 0 === $exitCode;
    }
}
