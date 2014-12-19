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

use Distill\Exception\IO\Input\FileUnknownFormatException;
use Distill\Format\FormatChainInterface;

interface FormatGuesserInterface
{
    /**
     * Guesses the file format based on features of the path (e.g. extension)
     * @param  string                     $path File path
     * @throws FileUnknownFormatException
     *
     * @return FormatChainInterface File format.
     */
    public function guess($path);
}
