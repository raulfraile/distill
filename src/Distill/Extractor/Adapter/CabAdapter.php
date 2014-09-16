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
use Distill\Format\Cab;
use Distill\Format\FormatInterface;

/**
 * Extracts files from Cab archives.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class CabAdapter extends AbstractAdapter
{

    /**
     * {@inheritdoc}
     */
    public function supports(FormatInterface $format)
    {
        return $format instanceof Cab && $this->hasSupportedMethods();
    }

}
