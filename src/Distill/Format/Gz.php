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

use Distill\Method;

/**
 * A GZIP file.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Gz implements FormatInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCompressionRatioLevel()
    {
        return FormatInterface::LEVEL_MIDDLE;
    }

    /**
     * {@inheritdoc}
     */
    public function getUncompressionSpeedLevel()
    {
        return FormatInterface::LEVEL_HIGHEST;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompressionSpeedLevel()
    {
        return FormatInterface::LEVEL_MIDDLE;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['gz', 'gzip'];
    }

    /**
     * {@inheritdoc}
     */
    public function getUncompressionMethods()
    {
        return [
            Method\GzipCommandMethod::getName(),
            Method\X7zCommandMethod::getName()
        ];
    }

}
