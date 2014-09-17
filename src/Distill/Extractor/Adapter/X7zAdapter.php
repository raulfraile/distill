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

use Distill\Format\FormatInterface;
use Distill\Format\X7z;

/**
 * Extracts files from 7z archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class X7zAdapter extends AbstractAdapter
{

    /**
     * {@inheritdoc}
     */
    public function supports(FormatInterface $format)
    {
        return $format instanceof X7z && $this->hasSupportedMethods();
    }

}
