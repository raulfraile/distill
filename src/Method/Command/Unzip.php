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

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Unzip extends AbstractCommandMethod
{
    const EXIT_CODE_WARNING_ZIPFILE = 1;
    const EXIT_CODE_GENERIC_ERROR_ZIPFILE = 2;
    const EXIT_CODE_SEVERE_ERROR_ZIPFILE = 3;
    const EXIT_CODE_ZIPFILE_NOT_FOUND = 9;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $command = 'unzip '.escapeshellarg($file).' -d '.escapeshellarg($target);

        $exitCode = $this->executeCommand($command, $a);

        switch ($exitCode) {
            case self::EXIT_CODE_WARNING_ZIPFILE:
            case self::EXIT_CODE_GENERIC_ERROR_ZIPFILE:
                throw new Exception\IO\Input\FileCorruptedException($file, Exception\IO\Input\FileCorruptedException::SEVERITY_LOW);
            case self::EXIT_CODE_SEVERE_ERROR_ZIPFILE:
            case self::EXIT_CODE_ZIPFILE_NOT_FOUND:
                throw new Exception\IO\Input\FileCorruptedException($file, Exception\IO\Input\FileCorruptedException::SEVERITY_HIGH);
        }

        return $this->isExitCodeSuccessful($exitCode);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = $this->existsCommand('unzip');
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
    public function isFormatSupported(Format\FormatInterface $format = null)
    {
        return $format instanceof Format\Simple\Zip;
    }
}
