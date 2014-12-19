<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format;

interface ComposedFormatInterface extends FormatInterface
{
    /**
     * Gets the ordered list of the formats that belongs the composed format.
     *
     * @return FormatInterface[] List of formats.
     */
    public function getComposedFormats();

    /**
     * Gets the canonical extension for the format.
     * @return mixed
     */
    public function getCanonicalExtension();
}
