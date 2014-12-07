<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill;

use Distill\Format\FormatInterface;

interface SupportCheckerInterface
{
    /**
     * Checks if there are supported methods for the given format.
     * @param FormatInterface $format Format.
     *
     * @return boolean TRUE if it is supported, FALSE otherwise.
     */
    public function isFormatSupported(FormatInterface $format);
}
