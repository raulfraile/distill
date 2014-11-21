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

    protected $extensionMap;

    /**
     * Constructor.
     * @param FormatInterface[] $formats Formats.
     */
    public function __construct(array $formats = [])
    {
        $this->formats = $formats;

        $this->extensionMap = [];
        foreach ($this->formats as $format) {
            $extensions = $format->getExtensions();

            foreach ($extensions as $extension) {
                $this->extensionMap[$extension] = $format;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function guess($file)
    {
        $extension = $this->getExtension($file);

        if (false === array_key_exists($extension, $this->extensionMap)) {
            throw new ExtensionNotSupportedException($extension);
        }

        return $this->extensionMap[$extension];
    }

    /**
     * Gets the extension of a file.
     * @param $file File path
     *
     * @return string File extension
     */
    protected function getExtension($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($extension, array('bz', 'bz2', 'gz', 'xz'))) {
            $filename  = pathinfo($file, PATHINFO_FILENAME);
            $subextension = pathinfo($filename, PATHINFO_EXTENSION);

            $completeExtension = sprintf('%s.%s', $subextension, $extension);

            if (array_key_exists($completeExtension, $this->extensionMap)) {
                $extension = $completeExtension;
            }
        }

        return $extension;
    }

}
