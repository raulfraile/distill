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
use Distill\File;
use Distill\Format;
use Distill\Method\AbstractMethod;
use Distill\Method\MethodInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Rar extends AbstractMethod
{

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, Format\FormatInterface $format)
    {
        if (!$this->isSupported()) {
            throw new MethodNotSupportedException($this);
        }

        if (false === $this->isFormatSupported($format)) {
            throw new FormatNotSupportedInMethodException($this, $format);
        }

        $rar = @\RarArchive::open($file);

        if (false === $rar) {
            return false;
        }

        $this->getFilesystem()->mkdir($target);

        foreach ($rar->getEntries() as $entry) {
            $entry->extract($target);
        }

        $rar->close();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        if (null === $this->supported) {
            $this->supported = extension_loaded('rar');
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
    public static function getUncompressionSpeedLevel(Format\FormatInterface $format = null)
    {
        return MethodInterface::SPEED_LEVEL_MIDDLE;
    }

    public function isFormatSupported(Format\FormatInterface $format)
    {
        return $format instanceof Format\Rar;
    }
}
