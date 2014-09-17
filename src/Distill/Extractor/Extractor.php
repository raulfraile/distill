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
use Pimple\Container;

class Extractor implements ExtractorInterface
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
            $method = $this->container['distill.method.' . $methodsKeys[$i]];
            if ($method->isSupported($format)) {
                $success = $method->extract($file, $path, $format);
            }

            $i++;
        }

        return $success;
    }

}
