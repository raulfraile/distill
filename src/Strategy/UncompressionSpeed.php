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

use Distill\FileInterface;

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
    protected function getPriorityValueForFile(FileInterface $file, array $methods)
    {
        $formatChain = $file->getFormatChain();

        return $this->getMaxUncompressionSpeedFormatChain($formatChain, $methods) + ($formatChain->getCompressionRatioLevel() / 10);
    }
}
