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
use Distill\Format\Xz;

/**
 * Extracts files from xz archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class XzAdapter extends AbstractAdapter
{

    /**
     * {@inheritdoc}
     */
    public function supports(FormatInterface $format)
    {
        return $format instanceof Xz && $this->hasSupportedMethods();
    }

}
