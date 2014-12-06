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
 * Minimum size strategy.
 *
 * The goal of this strategy is to try to use compressed files with better
 * compression ratio.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class MinimumSize extends AbstractStrategy
{

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'minimum_size';
    }

    /**
     * Order files based on the strategy.
     * @param File $file1 File 1
     * @param File $file2 File 2
     * @param MethodInterface[] $methods
     *
     * @return int
     */
    protected function order(File $file1, File $file2, array $methods)
    {
        $format1 = $file1->getFormat();
        $format2 = $file2->getFormat();

        $priority1 = $format1->getCompressionRatioLevel();
        $priority2 = $format2->getCompressionRatioLevel();

        // add uncompression speed for ties
        $priority1 += $this->getMaxUncompressionSpeedFormat($format1, $methods) / 10;
        $priority2 += $this->getMaxUncompressionSpeedFormat($format2, $methods) / 10;

        if ($priority1 == $priority2) {
            return 0;
        }

        return ($priority1 > $priority2) ? -1 : 1;
    }
}
