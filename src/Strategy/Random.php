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

/**
 * Random strategy.
 *
 * The goal of this strategy is to get a random file able to be decompressed.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Random implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'random';
    }

    /**
     * {@inheritdoc}
     */
    public function getPreferredFilesOrdered(array $files, array $methods = [])
    {
        if (empty($files)) {
            return [];
        }

        shuffle($files);

        return $files;
    }
}
