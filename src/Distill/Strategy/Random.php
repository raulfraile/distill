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

/**
 * Random strategy.
 *
 * The goal of this strategy is to get a random file able to be decompressed.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Random extends AbstractStrategy
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
    public function getPreferredFile(array $files)
    {
        if (empty($files)) {
            return null;
        }

        return $files[rand(0, count($files) - 1)];
    }

}
