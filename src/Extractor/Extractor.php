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

use Distill\Exception\FormatNotSupportedException;
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;

class Extractor implements ExtractorInterface
{

    /**
     * @var MethodInterface[]
     */
    protected $methods;

    /**
     * @var FormatInterface[]
     */
    protected $formats;

    /**
     * Constructor.
     * @param MethodInterface[] $methods
     * @param FormatInterface[] $formats
     */
    public function __construct(array $methods, array $formats)
    {
        $this->methods = [];
        foreach ($methods as $method) {
            $this->methods[$method->getName()] = $method;
        }

        $this->formats = [];
        foreach ($formats as $format) {
            $this->formats[$format->getName()] = $format;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extract($file, $path, FormatInterface $format)
    {
        if (false === array_key_exists($format->getName(), $this->formats)) {
            throw new FormatNotSupportedException($format);
        }

        $methodsKeys = $format->getUncompressionMethods();
        $methodsCount = count($methodsKeys);
        $i = 0;
        $success = false;
        while (!$success && $i < $methodsCount) {
            if (array_key_exists($methodsKeys[$i], $this->methods)) {
                $method = $this->methods[$methodsKeys[$i]];
                if ($method->isSupported($format)) {
                    $success = $method->extract($file, $path, $format);
                }
            }

            $i++;
        }

        return $success;
    }

}
