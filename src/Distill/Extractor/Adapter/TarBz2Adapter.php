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

use Distill\File;
use Distill\Format\FormatInterface;
use Distill\Format\TarBz2;

/**
 * Extracts files from tar.bz2 archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class TarBz2Adapter extends AbstractAdapter
{

    /**
     * {@inheritdoc}
     */
    public function supports(FormatInterface $format)
    {
        return $format instanceof TarBz2 && $this->hasSupportedMethods();
    }

}
