<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill;

use Distill\Format\FormatInterface;
use Pimple\Container;

class SupportChecker implements SupportCheckerInterface
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     * @param Container $container Container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function isFormatSupported(FormatInterface $format)
    {
        $methodsKeys = $format->getUncompressionMethods();
        $methodsCount = count($methodsKeys);
        $i = 0;
        $supported = false;
        while (!$supported && $i < $methodsCount) {
            $method = $this->container['distill.method.' . $methodsKeys[$i]];
            if ($method->isSupported($format)) {
                $supported = true;
            }

            $i++;
        }

        return $supported;
    }

}
