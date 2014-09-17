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

use Distill\Format;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class PharDataMethod extends AbstractMethod
{

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            return false;
        }

        try {
            $pharFormat = $this->getPharFormat($format);
            $archive = new \PharData($file, null, null, $pharFormat);
        } catch (\UnexpectedValueException $e) {
            return false;
        }

        if (null === $pharFormat || !$archive->isFileFormat($pharFormat)) {
            return false;
        }

        $archive->extractTo($target, null, true);

        return true;
    }

    /**
     * Gets the format of the phar file.
     * @param FormatInterface $format
     *
     * @return int|null
     */
    protected function getPharFormat(FormatInterface $format)
    {
        if ($format instanceof Format\Tar || $format instanceof Format\TarBz2) {
            return \Phar::TAR;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return !$this->isWindows() && class_exists('\\Phar');
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'phar_data';
    }

}
