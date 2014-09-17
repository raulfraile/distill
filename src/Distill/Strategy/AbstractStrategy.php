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

abstract class AbstractStrategy implements StrategyInterface
{

    /**
     * {@inheritdoc}
     */
    public function getPreferredFile(array $files)
    {
        usort($files, 'static::order');

        if (empty($files)) {
            return null;
        }

        return $files[0];
    }

}
