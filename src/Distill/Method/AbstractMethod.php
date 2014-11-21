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

use Symfony\Component\Process\Process;

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

}
