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
use Distill\Format\FormatInterface;
use Distill\Method\AbstractMethod;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Phar extends AbstractMethod
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
            $phar = new \Phar($file);
            $this->getFilesystem()->mkdir($target);
            $phar->extractTo($target, null, true);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return extension_loaded('Phar') &&
            (false === $this->isHhvm() || ($this->isHhvm() && in_array((string) ini_get('phar.readonly'), ['0', 'Off'])));
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

}
