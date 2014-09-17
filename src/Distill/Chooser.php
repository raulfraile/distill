<?php

namespace Distill;

use Distill\Format\FormatInterface;
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
     * @var File[]
     */
    protected $files;

    /**
     * Constructor.
     * @param StrategyInterface      $strategy
     * @param FormatGuesserInterface $formatGuesser
     */
    public function __construct(StrategyInterface $strategy, FormatGuesserInterface $formatGuesser)
    {
        $this->strategy = $strategy;
        $this->formatGuesser = $formatGuesser;
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
     * Sets the files to choose from.
     * @param File[] $files Files
     *
     * @return Chooser
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Adds a new file.
     * @param string               $filename File name
     * @param FormatInterface|null $format   Format
     *
     * @return Distill
     */
    public function addFile($filename, FormatInterface $format = null)
    {
        if (null === $format) {
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
     *
     * @return string Preferred file
     */
    public function getPreferredFile()
    {
        $preferredFile = $this->strategy->getPreferredFile($this->files);

        return $preferredFile->getPath();
    }

}
