<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format\Composed;

use Distill\Format\AbstractComposedFormat;
use Distill\Format\Simple\Bz2;
use Distill\Format\Simple\Tar;

class TarBz2 extends AbstractComposedFormat
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->formats = [
            new Bz2(),
            new Tar(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonicalExtension()
    {
        return 'tar.bz2';
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['tar.bz2', 'tar.bz', 'tbz2', 'tbz', 'tb2'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }
}
