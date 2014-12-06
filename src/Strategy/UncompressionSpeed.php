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
 * Uncompression speed strategy.
 *
 * The goal of this strategy is to try to use compressed files which
 * are faster to uncompress.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class UncompressionSpeed extends AbstractStrategy
{

    const STRATEGY_NAME = 'uncompression_speed';

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return self::STRATEGY_NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function order(File $file1, File $file2, array $methods)
    {
        $priority1 = $this->getMaxUncompressionSpeedFormat($file1->getFormat(), $methods);
        $priority2 = $this->getMaxUncompressionSpeedFormat($file2->getFormat(), $methods);

        if ($priority1 == $priority2) {
            return 0;
        }

        return ($priority1 > $priority2) ? -1 : 1;
    }
}
