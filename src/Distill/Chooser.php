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


    public function __construct(StrategyInterface $strategy, FormatGuesserInterface $formatGuesser)
    {
        $this->strategy = $strategy;
        $this->formatGuesser = $formatGuesser;
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
     * Gets the preferred file based on the chosen strategy.
     *
     * @return File Preferred file
     */
    public function getPreferredFile()
    {
        $preferredFile = $this->strategy->getPreferredFile($this->files);

        return $preferredFile->getPath();
    }

}