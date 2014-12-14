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

    protected function extractFormat($file, $path, FormatInterface $format)
    {
        $success = false;
        for ($i = 0, $methodsCount = count($this->methods); $i<$methodsCount && false === $success; $i++) {
            $method = $this->methods[$i];

            if ($method->isSupported() && $method->isFormatSupported($format)) {
                $success = $method->extract($file, $path, $format);
            }
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($file, $path, FormatChainInterface $chainFormat)
    {
        $chainFormats = $chainFormat->getChainFormats();
        foreach ($chainFormats as $format) {
            if (false === $this->supportChecker->isFormatSupported($format)) {
                throw new FileFormatNotSupportedException($file, $format);
            }
        }

        $success = true;
        $lastFile = $file;
        $tempDirectories = [];
        for ($i = 0, $formatsCount = count($chainFormats); $i<$formatsCount && true === $success; $i++) {
            if (($i+1) === $formatsCount) {
                // last
                $success = $this->extractFormat($lastFile, $path, $chainFormats[$i]);
            } else {
                $tempDirectory = $path.DIRECTORY_SEPARATOR.'step_'.$i;
                $tempDirectories[] = $tempDirectory;
                $success = $this->extractFormat($lastFile, $tempDirectory, $chainFormats[$i]);

                $iterator = new \FilesystemIterator($tempDirectory, \FilesystemIterator::SKIP_DOTS);

                while ($iterator->valid()) {
                    $extractedFile = $iterator->current();

                    $iterator->next();
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
