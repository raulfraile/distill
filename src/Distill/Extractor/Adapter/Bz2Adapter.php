<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Adapter;

use Distill\Format\Bz2;
use Distill\Format\FormatInterface;

/**
 * Extracts files from bzip2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class Bz2Adapter extends AbstractAdapter
{

    /**
     * {@inheritdoc}
     */
    public function supports(FormatInterface $format)
    {
        return $format instanceof Bz2 && $this->hasSupportedMethods();
    }

}
