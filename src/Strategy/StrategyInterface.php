<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Strategy;

use Distill\File;
use Distill\Method\MethodInterface;

/**
 * Strategy interface.
 *
 * Any strategy must implement this interface.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
interface StrategyInterface
{

    /**
     * Returns the preferred files in order.
     * @param File[]            $files
     * @param MethodInterface[] $methods
     *
     * @return File[]
     */
    public function getPreferredFilesOrdered(array $files, array $methods = []);

    /**
     * Gets the strategy key name.
     * @static
     *
     * @return string Method name
     */
    public static function getName();

}
