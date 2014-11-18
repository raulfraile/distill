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

use Distill\Exception\CorruptFileException;
use Distill\File;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class UnzipCommandMethod extends AbstractMethod
{

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

        if (self::EXIT_CODE_SEVERE_ERROR_ZIPFILE === $exitCode) {
            throw new CorruptFileException($file);
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
    public static function getName()
    {
        return 'unzip_command';
    }

}
