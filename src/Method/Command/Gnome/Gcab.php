<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Command\Gnome;

use Distill\Exception\IO\Input\FileCorruptedException;
use Distill\Format;
use Distill\Method\Command\AbstractCommandMethod;

/**
 * Extracts files from cab archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Gcab extends AbstractCommandMethod
{
    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        $this->checkSupport($format);

        $this->getFilesystem()->mkdir($target);
        $command = 'gcab -x '.escapeshellarg($file).' -C '.escapeshellarg($target);

        $exitCode = $this->executeCommand($command);
        if ($exitCode > 0) {
            throw new FileCorruptedException($file);
        }

        return $this->isExitCodeSuccessful($exitCode);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = $this->existsCommand('gcab');
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
        return $format instanceof Format\Simple\Cab;
    }
}
