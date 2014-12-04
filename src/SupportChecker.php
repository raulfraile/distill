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
use Distill\Method\MethodInterface;

class SupportChecker implements SupportCheckerInterface
{

    /**
     * @var MethodInterface[]
     */
    protected $methods;

    /**
     * Constructor.
     * @param MethodInterface[] $methods.
     */
    public function __construct(array $methods)
    {
        $this->methods = [];

        foreach ($methods as $method) {
            $this->methods[$method->getName()] = $method;
        }
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

            if (array_key_exists($methodsKeys[$i], $this->methods)) {
                $method = $this->methods[$methodsKeys[$i]];
                if ($method->isSupported($format)) {
                    $supported = true;
                }
            }

            $i++;
        }

        return $supported;
    }

}
