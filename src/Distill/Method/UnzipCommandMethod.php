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
use Distill\File;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class UnzipCommandMethod extends AbstractMethod
{

    const EXIT_CODE_WARNING_ZIPFILE = 1;
    const EXIT_CODE_GENERIC_ERROR_ZIPFILE = 2;
    const EXIT_CODE_SEVERE_ERROR_ZIPFILE = 3;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            return false;
        }

        $command = 'unzip '.escapeshellarg($file).' -d '.escapeshellarg($target);

        $exitCode = $this->executeCommand($command);

        switch ($exitCode) {
            case self::EXIT_CODE_WARNING_ZIPFILE:
            case self::EXIT_CODE_GENERIC_ERROR_ZIPFILE:
                throw new CorruptedFileException($file, CorruptedFileException::SEVERITY_LOW);
            case self::EXIT_CODE_SEVERE_ERROR_ZIPFILE:
                throw new CorruptedFileException($file, CorruptedFileException::SEVERITY_HIGH);
        }

        return $this->isExitCodeSuccessful($exitCode);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return !$this->isWindows() && $this->existsCommand('unzip');
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
