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

use Distill\Exception\CorruptedFileException;
use Distill\Exception\FormatNotSupportedInMethodException;
use Distill\File;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class X7zCommandMethod extends AbstractCommandMethod
{

    const EXIT_CODE_OK = 0;
    const EXIT_CODE_FATAL_ERROR = 2;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (false === $this->isSupported()) {
            return false;
        }

        if (false === $this->isFormatSupported($format)) {
            throw new FormatNotSupportedInMethodException($this, $format);
        }

        $this->getFilesystem()->mkdir($target);
        $command = '7z e -y '.escapeshellarg($file).' -o'.escapeshellarg($target);

        $exitCode = $this->executeCommand($command);

        if (self::EXIT_CODE_FATAL_ERROR === $exitCode) {
            throw new CorruptedFileException($file, CorruptedFileException::SEVERITY_HIGH);
        }

        return self::EXIT_CODE_OK === $exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return !$this->isWindows() && $this->existsCommand('7z');
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
