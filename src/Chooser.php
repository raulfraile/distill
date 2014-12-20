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

use Distill\Exception\FormatGuesserRequiredException;
use Distill\Exception\InvalidArgumentException;
use Distill\Exception\StrategyRequiredException;
use Distill\Format\FormatChainInterface;
use Distill\Method\MethodInterface;
use Distill\Strategy\StrategyInterface;

class Chooser
{
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
     * Support checker.
     * @var SupportCheckerInterface
     */
    protected $supportChecker;

    /**
     * Files to choose from.
     * @var FileInterface[]
     */
    protected $files;

    /**
     * Whether or not to exclude unsupported files.
     * @var boolean
     */
    protected $excludeUnsupported;

    /**
     * Available methods.
     * @var MethodInterface[]
     */
    protected $methods;

    /**
     * Constructor.
     * @param SupportCheckerInterface $supportChecker
     * @param StrategyInterface       $strategy
     * @param FormatGuesserInterface  $formatGuesser
     * @param MethodInterface[]       $methods
     */
    public function __construct(
        SupportCheckerInterface $supportChecker,
        StrategyInterface $strategy = null,
        FormatGuesserInterface $formatGuesser = null,
        array $methods = [])
    {
        $this->strategy = $strategy;
        $this->formatGuesser = $formatGuesser;
        $this->supportChecker = $supportChecker;
        $this->excludeUnsupported = true;
        $this->methods = $methods;
    }

    /**
     * Sets the format guesser.
     * @param FormatGuesserInterface $formatGuesser Format guesser
     *
     * @return Chooser
     */
    public function setFormatGuesser($formatGuesser)
    {
        $this->formatGuesser = $formatGuesser;

        return $this;
    }

    /**
     * Sets the strategy.
     * @param StrategyInterface $strategy Strategy
     *
     * @return Chooser
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * Gets the strategy.
     *
     * @return StrategyInterface Current strategy
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Sets the files to choose from.
     * @param FileInterface[] $files Files.
     *
     * @return Chooser
     */
    public function setFiles($files)
    {
        $this->files = [];

        foreach ($files as $file) {
            $this->addFile($file);
        }

        return $this;
    }

    /**
     * Adds a new file.
     * @param string                    $filename    File name.
     * @param FormatChainInterface|null $formatChain Format. If null, it is guessed.
     *
     * @throws FormatGuesserRequiredException
     * @return Chooser
     */
    public function addFile($filename, FormatChainInterface $formatChain = null)
    {
        if (null === $formatChain) {
            if (null === $this->formatGuesser) {
                throw new FormatGuesserRequiredException();
            }

            $formatChain = $this->formatGuesser->guess($filename);
        }

        $this->files[] = new File($filename, $formatChain);

        return $this;
    }

    /**
     * Adds new files that have the same name but different extensions.
     * @param string            $basename   Basename (e.g. 'my_file')
     * @param string[]          $extensions Extensions (e.g. ['zip', 'rar'])
     * @param FormatChainInterface[] $formatChains    Formats for each of the extensions. Supports indexed
     *                                      and associative arrays. If null, they are guessed.
     *
     * @throws FormatGuesserRequiredException
     *
     * @return Chooser
     */
    public function addFilesWithDifferentExtensions($basename, array $extensions, array $formatChains = [])
    {
        if (!empty($formatChains) && count($extensions) != count($formatChains)) {
            throw new InvalidArgumentException('formatChains', 'If present, it must contain the same number of elements as extensions passed');
        }

        $i = 0;
        foreach ($extensions as $extension) {
            $formatChain = null;

            if (array_key_exists($i, $formatChains)) {
                $formatChain = $formatChains[$i];
            }

            if (array_key_exists($extension, $formatChains)) {
                $formatChain = $formatChains[$extension];
            }

            $this->addFile($basename.'.'.$extension, $formatChain);
        }

        return $this;
    }

    /**
     * Gets all the files.
     *
     * @return FileInterface[] Files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Exclude files of not supported formats. This is the default behaviour.
     *
     * @return Chooser
     */
    public function excludeUnsupportedFormats()
    {
        $this->excludeUnsupported = true;

        return $this;
    }

    /**
     * Include files of not supported formats.
     *
     * @return Chooser
     */
    public function includeUnsupportedFormats()
    {
        $this->excludeUnsupported = false;

        return $this;
    }

    /**
     * Gets the preferred file based on the chosen strategy.
     * @throws StrategyRequiredException
     *
     * @return FileInterface Preferred file
     */
    public function getPreferredFile()
    {
        $preferredFiles = $this->getPreferredFilesOrdered();

        if (empty($preferredFiles)) {
            return null;
        }

        return $preferredFiles[0];
    }

    /**
     * Gets an ordered collection of preferred files.
     * @throws StrategyRequiredException
     *
     * @return FileInterface[]
     */
    public function getPreferredFilesOrdered()
    {
        if (null === $this->strategy) {
            throw new StrategyRequiredException();
        }

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($this->files, $this->methods);

        if (true === $this->excludeUnsupported) {
            return array_values(array_filter($preferredFiles, function (FileInterface $file) {
                return $this->supportChecker->isFormatChainSupported($file->getFormatChain());
            }));
        }

        return $preferredFiles;
    }
}
