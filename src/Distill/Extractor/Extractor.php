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

use Distill\File;
use Distill\Format\FormatInterface;

class Extractor implements ExtractorInterface
{

    /**
     * Available adapters.
     * @var Adapter\AdapterInterface[]
     */
    protected $adapters;

    /**
     * Constructor
     *
     */
    public function __construct(array $adapters = array())
    {
        $this->adapters = $adapters;
    }

    /**
     * {@inheritdoc}
     */
    public function addAdapter(Adapter\AdapterInterface $adapter)
    {
        $this->adapters[] = $adapter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($file, $path, FormatInterface $format)
    {
        $adaptersCount = count($this->adapters);
        $i = 0;
        $success = false;

        while (!$success && $i < $adaptersCount) {
            if ($this->adapters[$i]->supports($format)) {
                $success = $this->adapters[$i]->extract($file, $path, $format);
            }

            $i++;
        }

        return $success;
    }

}
