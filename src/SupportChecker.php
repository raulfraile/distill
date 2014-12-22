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

use Distill\Format\ComposedFormatInterface;
use Distill\Format\FormatChainInterface;
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;

class SupportChecker implements SupportCheckerInterface
{
    /**
     * Available methods.
     * @var MethodInterface[] $methods
     */
    protected $methods;

    /**
     * Available formats.
     * @var FormatInterface[]
     */
    protected $formats;

    /**
     * List of format classes.
     * @var string[]
     */
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
        for ($i = 0, $methodsCount = count($this->methods); $i<$methodsCount && false === $supported; $i++) {
            $method = $this->methods[$i];

            $supported = $method->isSupported() && $method->isFormatSupported($format);
        }

        if (false === $supported && $format instanceof ComposedFormatInterface) {
            $subformats = $format->getComposedFormats();
            $supportedSubformats = 0;
            for ($i = 0, $subformatsCount = count($subformats); $i<$subformatsCount; $i++) {
                if ($this->isFormatSupported($subformats[$i])) {
                    $supportedSubformats++;
                }
            }

            $supported = $supported || ($supportedSubformats === $subformatsCount);
        }

        return $supported;
    }

    /**
     * {@inheritdoc}
     */
    public function isFormatChainSupported(FormatChainInterface $formatChain)
    {
        if ($formatChain->isEmpty()) {
            return false;
        }

        foreach ($formatChain->getChainFormats() as $format) {
            if (false === $this->isFormatSupported($format)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnsupportedFormatsFromChain(FormatChainInterface $formatChain)
    {
        $formats = [];

        foreach ($formatChain->getChainFormats() as $format) {
            if (false === $this->isFormatSupported($format)) {
                $formats[] = $format;
            }
        }

        return $formats;
    }
}
