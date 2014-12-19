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

use Distill\Exception\IO\Input\FileCorruptedException;
use Distill\Format;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class GnuTar extends AbstractCommandMethod
{
    const EXIT_CODE_OK = 0;
    const EXIT_CODE_SOME_FILES_DIFFER = 1;
    const EXIT_CODE_FATAL_ERROR = 2;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $this->getFilesystem()->mkdir($target);

        $tarOptions = ['x', 'v', 'f'];

        if ($format instanceof Format\Composed\TarBz2) {
            array_unshift($tarOptions, 'j');
        } elseif ($format instanceof Format\Composed\TarGz) {
            array_unshift($tarOptions, 'z');
        } elseif ($format instanceof Format\Composed\TarXz) {
            array_unshift($tarOptions, 'J');
        }

        $command = sprintf("tar -%s %s -C %s", implode('', $tarOptions), escapeshellarg($file), escapeshellarg($target));

        $exitCode = $this->executeCommand($command);

        if (self::EXIT_CODE_FATAL_ERROR === $exitCode || self::EXIT_CODE_SOME_FILES_DIFFER === $exitCode) {
            throw new FileCorruptedException($file, FileCorruptedException::SEVERITY_HIGH);
        }

        return self::EXIT_CODE_OK === $exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = $this->existsCommand('tar');
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
        return $format instanceof Format\Simple\Tar
        || $format instanceof Format\Composed\TarBz2
        || $format instanceof Format\Composed\TarGz
        || $format instanceof Format\Composed\TarXz;
    }
}
