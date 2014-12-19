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
 * Extracts files from gzip archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class GnuGzip extends AbstractCommandMethod
{
    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $this->getFilesystem()->mkdir($target);

        $copiedFile = $target.DIRECTORY_SEPARATOR.basename($file);
        $this->getFilesystem()->copy($file, $copiedFile);

        $command = sprintf("gzip -d -N %s", escapeshellarg($copiedFile));

        $exitCode = $this->executeCommand($command);

        if ($exitCode > 0) {
            throw new FileCorruptedException($file);
        }

        return 0 === $exitCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = $this->existsCommand('gzip');
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
        return $format instanceof Format\Simple\Gz;
    }
}
