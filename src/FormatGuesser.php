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

use Distill\Format\ComposedFormatInterface;
use Distill\Format\FormatChain;
use Distill\Format\FormatInterface;

class FormatGuesser implements FormatGuesserInterface
{
    /**
     * Maps extensions and formats
     * @var FormatInterface[]
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
            if ($format instanceof ComposedFormatInterface) {
                $extensions = $format->getExtensions();
                $canonicalExtension = $format->getCanonicalExtension();

                foreach ($extensions as $extension) {
                    $this->composedExtensions[$extension] = $canonicalExtension;
                }
            }

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
        $extensions = $this->getExtensions($file);

        if (empty($extensions)) {
            throw new Exception\IO\Input\FileUnknownFormatException($file);
        }

        $formats = [];
        for ($i = 0, $extensionsNumber = count($extensions); $i < $extensionsNumber; $i++) {
            // check for combined
            $combinedExtension = implode('.', array_slice($extensions, $i, 2));
            if ((($i+1) < $extensionsNumber) && array_key_exists($combinedExtension, $this->extensionMap)) {
                $formats[] = $this->extensionMap[$combinedExtension];
                $i++;
            } else {
                $formats[] = $this->extensionMap[$extensions[$i]];
            }
        }

        $formatChain = new FormatChain(array_reverse($formats));

        return $formatChain;
    }

    /**
     * Gets the chain of recognized extensions of a file.
     * @param string $file File path.
     *
     * @return string[] Recognized extensions.
     */
    protected function getExtensions($file)
    {
        $basename = strtolower(pathinfo($file, PATHINFO_BASENAME));

        // normalize
        foreach ($this->composedExtensions as $composedExtension => $canonicalExtension) {
            $basename = preg_replace('/\.'.preg_quote($composedExtension).'(\.|$)/', '.'.$canonicalExtension.'\\1', $basename);
        }

        $extensions = explode('.', $basename);
        $extensions = array_reverse(array_slice($extensions, 1));

        $recognizedExtensions = [];

        $i = 0;
        $extensionsNumber = count($extensions);

        while ($i < $extensionsNumber && array_key_exists($extensions[$i], $this->extensionMap)) {
            $recognizedExtensions[] = $extensions[$i];
            $i++;
        }

        return array_reverse($recognizedExtensions);
    }
}
