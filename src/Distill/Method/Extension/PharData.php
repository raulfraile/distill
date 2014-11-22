<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Extension;

use Distill\Exception\FormatNotSupportedInMethodException;
use Distill\Exception\MethodNotSupportedException;
use Distill\Format;
use Distill\Format\FormatInterface;
use Distill\Method\AbstractMethod;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class PharData extends AbstractMethod
{

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            throw new MethodNotSupportedException($this);
        }

        if (false === $this->isFormatSupported($format)) {
            throw new FormatNotSupportedInMethodException($this, $format);
        }

        try {
            $pharFormat = $this->getPharFormat($format);
            $archive = new \PharData($file/*, null, null, $pharFormat*/);
            $archive->extractTo($target, null, true);
        } catch (\Exception $e) {
            return false;
        }

        /*if (null === $pharFormat || !$archive->isFileFormat($pharFormat)) {
            return false;
        }*/



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
        if ($format instanceof Format\Tar || $format instanceof Format\TarBz2 || $format instanceof Format\TarGz) {
            return \Phar::TAR;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return extension_loaded('Phar');
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
