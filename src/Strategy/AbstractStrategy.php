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
use Distill\Format\ComposedFormatInterface;
use Distill\Format\FormatChainInterface;
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;

abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPreferredFilesOrdered(array $files, array $methods = [])
    {
        usort($files, function (FileInterface $file1, FileInterface $file2) use ($methods) {
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

        if ($format instanceof ComposedFormatInterface) {
            $maxSpeedComposed = MethodInterface::SPEED_LEVEL_LOWEST;
            $subformats = $format->getComposedFormats();
            foreach ($subformats as $subformat) {
                $maxSpeedComposed = max($maxSpeedComposed, $this->getMaxUncompressionSpeedFormat($subformat, $methods) / count($subformats));
            }
        }

        return $maxSpeed;
    }

    protected function getMaxUncompressionSpeedFormatChain(FormatChainInterface $formatChain, array $methods)
    {
        $maxSpeed = MethodInterface::SPEED_LEVEL_LOWEST;

        foreach ($formatChain->getChainFormats() as $format) {
            $maxSpeed = max($maxSpeed, $this->getMaxUncompressionSpeedFormat($format, $methods));
        }

        return $maxSpeed;
    }

    /**
     * Order files based on the strategy.
     * @param FileInterface     $file1   File 1.
     * @param FileInterface     $file2   File 2.
     * @param MethodInterface[] $methods
     *
     * @return int
     */
    protected function order(FileInterface $file1, FileInterface $file2, array $methods)
    {
        $priority1 = $this->getPriorityValueForFile($file1, $methods);
        $priority2 = $this->getPriorityValueForFile($file2, $methods);

        if ($priority1 == $priority2) {
            return 0;
        }

        return ($priority1 > $priority2) ? -1 : 1;
    }

    /**
     * Gets the priority value for a format.
     * @param FileInterface     $format  File.
     * @param MethodInterface[] $methods Available methods.
     *
     * @return float
     */
    abstract protected function getPriorityValueForFile(FileInterface $file, array $methods);
}
