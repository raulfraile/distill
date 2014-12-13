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
    public function getComposedFormats();


    public function getCanonicalExtension();
}
