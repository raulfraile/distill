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
 * Extracts compressed files using the 7zip/p7zip tool.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class x7zip extends AbstractCommandMethod
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
        // In Unix systems, a port of 7-Zip is used: p7zip, and is divided in 3 packages:
        //  - p7zip: Only provides support for 7z files
        //  - p7zip-full: Provides support for many other formats (rar not included)
        //  - p7zip-rar: Provides support for rar files

        if ($format instanceof Format\x7z) {
            return true;
        }

        if ($format instanceof Format\Bz2 ||
            $format instanceof Format\Cab ||
            $format instanceof Format\Dmg ||
            $format instanceof Format\Gz  ||
            $format instanceof Format\Rar ||
            $format instanceof Format\Tar ||
            $format instanceof Format\Xz  ||
            $format instanceof Format\Zip) {
            return $this->checkFormatSupport($format);
        }

        return false;
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

        $exitCode = $this->executeCommand('7z t ' . $samples[Format\FormatInterface::SAMPLE_REGULAR]);

        return 0 === $exitCode;
    }
}
