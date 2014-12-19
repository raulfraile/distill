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
use Distill\Format\Simple\Gz;
use Distill\Format\Simple\Tar;

class TarGz extends AbstractComposedFormat
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->formats = [
            new Gz(),
            new Tar(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonicalExtension()
    {
        return 'tar.gz';
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['tar.gz', 'tgz'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }
}
