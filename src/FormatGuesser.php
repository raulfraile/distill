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
     * Maps extensions and formats
     * @var string[]
     */
    protected $extensionMap;

    /**
     * Contains root extensions that may contain subextensions.
     * @var string[]
     */
    protected $composedExtensions = [];

    /**
     * Constructor.
     * @param FormatInterface[] $formats Formats.
     */
    public function __construct(array $formats = [])
    {
        $this->extensionMap = [];
        foreach ($formats as $format) {
            $extensions = $format->getExtensions();

            foreach ($extensions as $extension) {
                $this->extensionMap[$extension] = $format;

                if (false !== $positionDot = strpos($extension, '.')) {
                    $this->composedExtensions[] = substr($extension, $positionDot + 1);
                }
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
     * @param string $file File path
     *
     * @return string File extension
     */
    protected function getExtension($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($extension, $this->composedExtensions)) {
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
