<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format;

abstract class AbstractComposedFormat extends AbstractFormat implements ComposedFormatInterface
{
    /** @var FormatInterface[] */
    protected $formats;

    /**
     * {@inheritdoc}
     */
    public function getComposedFormats()
    {
        return $this->formats;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompressionRatioLevel()
    {
        $maxLevel = FormatInterface::RATIO_LEVEL_LOWEST;

        foreach ($this->formats as $format) {
            $maxLevel = max($maxLevel, $format->getCompressionRatioLevel());
        }

        return $maxLevel;
    }
}
