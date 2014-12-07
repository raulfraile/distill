<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor;

use Distill\Exception\IO\Input\FormatNotSupportedException;
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Distill\SupportCheckerInterface;

class Extractor implements ExtractorInterface
{

    /**
     * @var MethodInterface[]
     */
    protected $methods;

    /**
     * @var SupportCheckerInterface $supportChecker
     */
    protected $supportChecker;

    /**
     * Constructor.
     * @param MethodInterface[]       $methods
     * @param SupportCheckerInterface $supportChecker
     */
    public function __construct(array $methods, SupportCheckerInterface $supportChecker)
    {
        $this->methods = $methods;
        $this->supportChecker = $supportChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($file, $path, FormatInterface $format)
    {
        if (false === $this->supportChecker->isFormatSupported($format)) {
            throw new FormatNotSupportedException($format);
        }

        $success = false;

        for ($i=0, $methodsCount = count($this->methods); $i<$methodsCount && false === $success; $i++) {
            $method = $this->methods[$i];
            if ($method->isSupported() && $method->isFormatSupported($format)) {
                $success = $method->extract($file, $path, $format);
            }
        }

        return $success;
    }

}
