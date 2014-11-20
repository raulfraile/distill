<?php

namespace Distill;

use Distill\Exception\FormatGuesserRequiredException;
use Distill\Exception\StrategyRequiredException;
use Distill\Format\FormatInterface;
use Distill\Strategy\StrategyInterface;
use Distill\SupportCheckerInterface;

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
     * @var File[]
     */
    protected $files;

    /**
     * Constructor.
     * @param SupportCheckerInterface $supportChecker
     * @param StrategyInterface $strategy
     * @param FormatGuesserInterface $formatGuesser
     */
    public function __construct(
        SupportCheckerInterface $supportChecker,
        StrategyInterface $strategy = null,
        FormatGuesserInterface $formatGuesser = null)
    {
        $this->strategy = $strategy;
        $this->formatGuesser = $formatGuesser;
        $this->supportChecker = $supportChecker;
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
     * @param File[] $files Files
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
     * @param string $filename File name
     * @param FormatInterface|null $format Format
     *
     * @throws Exception\FormatGuesserRequiredException
     * @return Chooser
     */
    public function addFile($filename, FormatInterface $format = null)
    {
        if (null === $format) {
            if (null === $this->formatGuesser) {
                throw new FormatGuesserRequiredException();
            }

            $format = $this->formatGuesser->guess($filename);
        }

        $this->files[] = new File($filename, $format);

        return $this;
    }

    /**
     * Gets all the files.
     *
     * @return File[] Files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Gets the preferred file based on the chosen strategy.
     * @throws Exception\StrategyRequiredException
     *
     * @return string Preferred file
     */
    public function getPreferredFile()
    {
        if (null === $this->strategy) {
            throw new StrategyRequiredException();
        }

        $preferredFiles = $this->strategy->getPreferredFilesOrdered($this->files);

        // get the first file that is supported
        $supportedFile = null;
        for ($supportedFile = null, $i = 0; $i < count($preferredFiles) && null === $supportedFile; $i++) {
            if ($this->supportChecker->isFormatSupported($preferredFiles[$i]->getFormat())) {
                $supportedFile = $preferredFiles[$i];
            }
        }

        return $supportedFile;
    }

}
