<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor;

use Distill\Exception\IO\Input\FileCorruptedException;
use Distill\Exception\IO\Input\FileFormatNotSupportedException;
use Distill\Extractor\Util\Filesystem;
use Distill\Format\FormatChainInterface;
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Distill\SupportCheckerInterface;

class Extractor implements ExtractorInterface
{
    /**
     * @var MethodInterface[]
     */
    protected $methods;

    /**
     * @var SupportCheckerInterface $supportChecker
     */
    protected $supportChecker;

    /**
     * @var Filesystem $filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     * @param MethodInterface[]       $methods
     * @param SupportCheckerInterface $supportChecker
     */
    public function __construct(array $methods, SupportCheckerInterface $supportChecker)
    {
        $this->methods = $methods;
        $this->supportChecker = $supportChecker;
        $this->filesystem = new Filesystem();
    }

    /**
     * Extracts a compressed file of a given format.
     * @param string $source
     * @param string $target
     * @param FormatInterface $format
     *
     * @return bool
     */
    protected function extractFormat($source, $target, FormatInterface $format)
    {
        $success = false;
        for ($i = 0, $methodsCount = count($this->methods); $i<$methodsCount && false === $success; $i++) {
            $method = $this->methods[$i];

            if ($method->isSupported() && $method->isFormatSupported($format)) {
                $success = $method->extract($source, $target, $format);
            }
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($source, $target, FormatChainInterface $chainFormat)
    {
        $chainFormats = $chainFormat->getChainFormats();
        foreach ($chainFormats as $format) {
            if (false === $this->supportChecker->isFormatSupported($format)) {
                throw new FileFormatNotSupportedException($source, $format);
            }
        }

        $success = true;
        $lastFile = $source;
        $tempDirectories = [];
        for ($i = 0, $formatsCount = count($chainFormats); $i<$formatsCount && true === $success; $i++) {
            if (($i+1) === $formatsCount) {
                // last
                $success = $this->extractFormat($lastFile, $target, $chainFormats[$i]);
            } else {
                $tempDirectory = $target.DIRECTORY_SEPARATOR.'step_'.$i;
                $tempDirectories[] = $tempDirectory;
                $success = $this->extractFormat($lastFile, $tempDirectory, $chainFormats[$i]);

                // look for the uncompressed file
                $iterator = new \FilesystemIterator($tempDirectory, \FilesystemIterator::SKIP_DOTS);
                $extractedFile = null;
                while ($iterator->valid()) {
                    $extractedFile = $iterator->current();

                    $iterator->next();
                }

                if (null === $extractedFile) {
                    throw new FileCorruptedException($lastFile);
                }

                $lastFile = $extractedFile->getRealPath();
            }
        }

        // clean temp directories
        foreach ($tempDirectories as $directory) {
            $this->filesystem->remove($directory);
        }

        return $success;
    }
}
