<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method;

use Distill\Exception\CorruptedFileException;
use Distill\Format\FormatInterface;
use Distill\Format\TarBz2;
use Distill\Format\TarGz;
use Distill\Format\TarXz;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class TarCommandMethod extends AbstractMethod
{

    const EXIT_CODE_OK = 0;
    const EXIT_CODE_SOME_FILES_DIFFER = 1;
    const EXIT_CODE_FATAL_ERROR = 2;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            return false;
        }

        @mkdir($target);

        $tarOptions = ['x', 'v', 'f'];

        if ($format instanceof TarBz2) {
            array_unshift($tarOptions, 'j');
        } elseif ($format instanceof TarGz) {
            array_unshift($tarOptions, 'z');
        } elseif ($format instanceof TarXz) {
            array_unshift($tarOptions, 'J');
        }

        $command = sprintf("tar -%s %s -C %s", implode('', $tarOptions), escapeshellarg($file), escapeshellarg($target));

        $exitCode = $this->executeCommand($command);

        if (self::EXIT_CODE_FATAL_ERROR === $exitCode || self::EXIT_CODE_SOME_FILES_DIFFER === $exitCode) {
            throw new CorruptedFileException($file, CorruptedFileException::SEVERITY_HIGH);
        }

        return self::EXIT_CODE_OK === $exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return !$this->isWindows() && $this->existsCommand('tar');
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
