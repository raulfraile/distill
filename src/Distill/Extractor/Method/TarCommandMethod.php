<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Method;
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

        return $this->executeCommand($command);
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
    public static function getName()
    {
        return 'tar_command';
    }

}
