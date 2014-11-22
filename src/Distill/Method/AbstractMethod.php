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

use Distill\Format\FormatInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractMethod implements MethodInterface
{

    protected function isExitCodeSuccessful($code)
    {
        return 0 === $code;
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        $className = static::getClass();
        $className = str_replace('\\', '', $className);
        $className = preg_replace('/^DistillMethod/', '', $className);
        $className = strtolower($className);

        return $className;
    }

    /**
     * Checks if the method is registered for the format.
     * @param FormatInterface $format Format.
     *
     * @return bool TRUE if it is registered, FALSE otherwise.
     */
    protected function isFormatSupported(FormatInterface $format)
    {
        return in_array(static::getName(), $format->getUncompressionMethods());
    }

    protected function getFilesystem()
    {
        return new Filesystem();
    }

    protected function isHhvm() {
        return defined('HHVM_VERSION');
    }

}
