<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Method;

use Distill\Format\FormatInterface;

interface MethodInterface
{

    public function extract($file, $path, FormatInterface $format);

    public function isSupported();
}
