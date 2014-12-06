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
     * @var FormatInterface[]
     */
    protected $formats;

    protected $formatsMap;

    /**
     * Constructor.
     * @param MethodInterface[] $methods.
     * @param FormatInterface[] $formats.
     */
    public function __construct(array $methods, array $formats)
    {
        $this->methods = $methods;
        $this->formats = $formats;

        $this->formatsMap = [];
        foreach ($this->formats as $format) {
            $this->formatsMap[] = $format->getClass();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isFormatSupported(FormatInterface $format)
    {
        if (false === in_array(get_class($format), $this->formatsMap)) {
            return false;
        }

        $supported = false;
        for ($i=0, $methodsCount = count($this->methods); $i<$methodsCount && false === $supported; $i++) {
            $method = $this->methods[$i];

            $supported = $method->isSupported() && $method->isFormatSupported($format);
        }

        return $supported;
    }

}
