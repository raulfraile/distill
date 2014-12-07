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
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;

abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPreferredFilesOrdered(array $files, array $methods = [])
    {
        usort($files, function (File $file1, File $file2) use ($methods) {
            return static::order($file1, $file2, $methods);
        });

        return $files;
    }

    /**
     * @param FormatInterface   $format
     * @param MethodInterface[] $methods
     *
     * @return int
     */
    protected function getMaxUncompressionSpeedFormat(FormatInterface $format, array $methods)
    {
        $maxSpeed = MethodInterface::SPEED_LEVEL_LOWEST;

        foreach ($methods as $method) {
            if ($method->isSupported() && $method->isFormatSupported($format)) {
                $maxSpeed = max($maxSpeed, $method->getUncompressionSpeedLevel($format));
            }
        }

        return $maxSpeed;
    }

    /**
     * Order files based on the strategy.
     * @param File              $file1   File 1
     * @param File              $file2   File 2
     * @param MethodInterface[] $methods
     *
     * @return int
     */
    abstract protected function order(File $file1, File $file2, array $methods);
}
