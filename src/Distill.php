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

use Distill\Exception\IO\Output\TargetDirectoryNotWritableException;
use Distill\Extractor\ExtractorInterface;
use Distill\Format\FormatChain;
use Distill\Format\FormatChainInterface;
use Distill\Format\FormatInterface;
use Distill\Extractor\Util\Filesystem;
use Symfony\Component\Filesystem\Filesystem as SfFilesystem;
use Distill\Strategy\StrategyInterface;
use Pimple\Container;

class Distill
{
    /**
     * Compressed file extractor.
     * @var ExtractorInterface Extractor
     */
    protected $extractor;

    /**
     * Strategy.
     * @var StrategyInterface
     */
    protected $strategy;

    /**
     * Format guesser.
     * @var FormatGuesserInterface
     */
    protected $formatGuesser;

    /**
     * Files.
     * @var FileInterface[]
     */
    protected $files;

    /**
     * Container.
     * @var Container
     */
    protected $container;

    /**
     * Whether or not the container has been initialized.
     * @var bool
     */
    protected $initialized;

    /**
     * Disabled methods.
     * @var string[]
     */
    protected $disabledMethods;

    /**
     * Disabled formats.
     * @var string[]
     */
    protected $disabledFormats;

    /**
     * Filesystem object to perform fs operations.
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initialized = false;
        $this->disabledMethods = [];
        $this->disabledFormats = [];

        // uses the special, internal filesystem due to an issue with Symfony's
        // rename across drives. This was adapted from Composer
        $this->filesystem = new Filesystem();
    }

    /**
     * Initialize the DIC.
     */
    protected function initialize()
    {
        $this->container = new Container();

        $containerProvider = new ContainerProvider($this->disabledMethods, $this->disabledFormats);
        $this->container->register($containerProvider);

        $this->initialized = false;
    }

    /**
     * Initialize the DIC if it has not been initialized already.
     */
    protected function initializeIfNotInitialized()
    {
        if (false === $this->initialized) {
            $this->initialize();
        }
    }

    /**
     * Extracts the compressed file into the given path.
     * @param string                 $source Compressed file
     * @param string                 $target Destination path
     * @param Format\FormatInterface $format
     *
     * @throws Exception\IO\Input\FileEmptyException
     * @throws Exception\IO\Input\FileFormatNotSupportedException
     * @throws Exception\IO\Input\FileNotFoundException
     * @throws Exception\IO\Input\FileNotReadableException
     * @throws Exception\IO\Input\FileUnknownFormatException
     * @throws Exception\IO\Output\TargetDirectoryNotWritableException
     *
     * @return bool
     */
    public function extract($source, $target, FormatInterface $format = null)
    {
        $this->initializeIfNotInitialized();

        if (false === file_exists($source)) {
            throw new Exception\IO\Input\FileNotFoundException($source);
        }

        if (false === is_readable($source)) {
            throw new Exception\IO\Input\FileNotReadableException($source);
        }

        if (0 === filesize($source)) {
            throw new Exception\IO\Input\FileEmptyException($source);
        }

        if (true === file_exists($target) && false === is_writable($target)) {
            throw new Exception\IO\Output\TargetDirectoryNotWritableException($target);
        }

        if (null === $format) {
            $formatChain = $this->container['format_guesser']->guess($source);

            if (0 === count($formatChain)) {
                throw new Exception\IO\Input\FileUnknownFormatException($source);
            }
        } else {
            $formatChain = new FormatChain([$format]);
        }

        if (false === $this->isFormatChainSupported($formatChain)) {
            $unsupportedFormats = $this->getSupportChecker()->getUnsupportedFormatsFromChain($formatChain);
            throw new Exception\IO\Input\FileFormatNotSupportedException($source, $unsupportedFormats[0]);
        }

        return $this->container['extractor.extractor']->extract($source, $target, $formatChain);
    }

    /**
     * Extracts the compressed file and copies the files from the root directory
     * only if the compressed file contains a single directory.
     * @param string                 $file   Compressed file.
     * @param string                 $path   Destination path.
     * @param Format\FormatInterface $format Format.
     *
     * @throws Exception\IO\Input\FileEmptyException
     * @throws Exception\IO\Input\FileFormatNotSupportedException
     * @throws Exception\IO\Input\FileNotFoundException
     * @throws Exception\IO\Input\FileNotReadableException
     * @throws Exception\IO\Output\NotSingleDirectoryException
     * @throws Exception\IO\Output\TargetDirectoryNotWritableException
     *
     * @return bool
     */
    public function extractWithoutRootDirectory($file, $path, FormatInterface $format = null)
    {
        $this->initializeIfNotInitialized();

        // extract to a temporary place
        $tempDirectory = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid(time()).DIRECTORY_SEPARATOR;
        $this->extract($file, $tempDirectory, $format);

        // move directory
        $iterator = new \FilesystemIterator($tempDirectory, \FilesystemIterator::SKIP_DOTS);

        $hasSingleRootDirectory = true;
        $singleRootDirectoryName = null;
        $numberDirectories = 0;

        while ($iterator->valid() && $hasSingleRootDirectory) {
            $uncompressedResource = $iterator->current();

            if (false === $uncompressedResource->isDir()) {
                $hasSingleRootDirectory = false;
            }

            $singleRootDirectoryName = $uncompressedResource->getRealPath();
            $numberDirectories++;

            if ($numberDirectories > 1) {
                $hasSingleRootDirectory = false;
            }

            $iterator->next();
        }

        if (false === $hasSingleRootDirectory) {
            // it is not a compressed file with a single directory
            $this->filesystem->remove($tempDirectory);

            throw new Exception\IO\Output\NotSingleDirectoryException($file);
        }

        $workingDirectory = getcwd();
        if ($workingDirectory === realpath($path)) {
            if (dirname($workingDirectory) === $workingDirectory) {
                // root directory
                throw new TargetDirectoryNotWritableException($workingDirectory);
            }
            
            chdir(dirname($workingDirectory));

            $sfFilesystem = new SfFilesystem();
            $filesRemove = new \FilesystemIterator($workingDirectory, \FilesystemIterator::SKIP_DOTS);
            $sfFilesystem->remove($filesRemove);
            $sfFilesystem->mirror($singleRootDirectoryName, $workingDirectory);

            chdir($workingDirectory);
        } else {
            $this->filesystem->remove($path);
            $this->filesystem->rename($singleRootDirectoryName, $path);
        }

        return true;
    }

    /**
     * Gets the file chooser.
     *
     * @return Chooser
     */
    public function getChooser()
    {
        $this->initializeIfNotInitialized();

        return $this->container['chooser'];
    }

    /**
     * Checks whether the format is supported.
     * @param FormatInterface $format Format to be checked.
     *
     * @return boolean
     */
    public function isFormatSupported(FormatInterface $format)
    {
        $this->initializeIfNotInitialized();

        return $this->getSupportChecker()->isFormatSupported($format);
    }

    /**
     * Checks whether the format chain is supported.
     * @param FormatChainInterface $formatChain Format chain to be checked.
     *
     * @return boolean Returns TRUE If the chain is supported, FALSE otherwise.
     */
    protected function isFormatChainSupported(FormatChainInterface $formatChain)
    {
        $this->initializeIfNotInitialized();

        return $this->getSupportChecker()->isFormatChainSupported($formatChain);
    }

    /**
     * Disables a method.
     * @param string $methodName Method name (e.g. Method\Command\Unzip::getName()).
     *
     * @return Distill
     */
    public function disableMethod($methodName)
    {
        $this->disabledMethods[] = $methodName;
        $this->initialized = false;

        return $this;
    }

    /**
     * Disables a format.
     * @param  string  $formatName Format name (e.g. Format\Zip::getName()).
     * @return Distill
     */
    public function disableFormat($formatName)
    {
        $this->disabledFormats[] = $formatName;
        $this->initialized = false;

        return $this;
    }

    /**
     * Gets the file chooser.
     *
     * @return SupportCheckerInterface
     */
    public function getSupportChecker()
    {
        $this->initializeIfNotInitialized();

        return $this->container['support_checker'];
    }
}
