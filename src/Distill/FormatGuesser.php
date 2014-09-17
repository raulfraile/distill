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

use Distill\Exception\ExtensionNotSupportedException;
use Distill\Format\FormatInterface;

class FormatGuesser implements FormatGuesserInterface
{

    /**
     * @var FormatInterface[]
     */
    protected $formats;

    public function __construct(array $formats = [])
    {
        $this->formats = $formats;
    }

    /**
     * {@inheritdoc}
     */
    public function guess($file)
    {
        $extension = $this->getExtension($file);

        $format = null;
        $formatsNumber = count($this->formats);
        $i = 0;
        while (null === $format && $i < $formatsNumber) {
            if (in_array($extension, $this->formats[$i]->getExtensions())) {
                $format = $this->formats[$i];
            }
            $i++;
        }

        if (null === $format) {
            throw new ExtensionNotSupportedException($extension);
        }

        return $format;
    }

    protected function getExtension($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($extension, array('bz', 'bz2', 'gz', 'xz'))) {
            $filename  = pathinfo($file, PATHINFO_FILENAME);
            $subextension = pathinfo($filename, PATHINFO_EXTENSION);

            if ("" !== $subextension) {
                $extension = sprintf('%s.%s', $subextension, strtolower($extension));
            }
        }

        return $extension;
    }

}
