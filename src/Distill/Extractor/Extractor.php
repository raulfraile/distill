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

use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;

class Extractor implements ExtractorInterface
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
    public function extract($file, $path, FormatInterface $format)
    {
        $methodsKeys = $format->getUncompressionMethods();
        $methodsCount = count($methodsKeys);
        $i = 0;
        $success = false;
        while (!$success && $i < $methodsCount) {
            $method = $this->methods[$methodsKeys[$i]];
            if ($method->isSupported($format)) {
                $success = $method->extract($file, $path, $format);
            }

            $i++;
        }

        return $success;
    }

}
