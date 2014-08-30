<?php

namespace Distill;

use Distill\Extractor\Extractor;
use Distill\Extractor\ExtractorInterface;
use Distill\Format\FormatGuesser;
use Distill\Strategy\MinimumSize;
use Distill\Strategy\StrategyInterface;
use Distill\Format\FormatInterface;
use Distill\Format\FormatGuesserInterface;

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
     * @var FormatGuesserInterface
     */
    protected $formatGuesser;

    /**
     * Files.
     * @var File[]
     */
    protected $files;


    /**
     * Constructor.
     * @param ExtractorInterface     $extractor
     * @param StrategyInterface      $strategy
     * @param FormatGuesserInterface $formatGuesser
     */
    public function __construct(
        ExtractorInterface $extractor = null,
        StrategyInterface $strategy = null,
        FormatGuesserInterface $formatGuesser = null
    )
    {
        if (null === $extractor) {
            $extractor = new Extractor();
        }
        $this->extractor = $extractor;

        if (null === $strategy) {
            $strategy = new MinimumSize();
        }
        $this->strategy = $strategy;

        if (null === $formatGuesser) {
            $formatGuesser = new FormatGuesser();
        }
        $this->formatGuesser = $formatGuesser;

        $this->files = array();
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
        return $this->strategy->getPreferredFile($this->files);
    }


    public function downloadAndExtract($path)
    {
        return $this->extract($this->getPreferredFile(), $path);
    }

    /**
     * Extracts the compressed file into the given path.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract(File $file, $path)
    {
        return $this->extractor->extract($file, $path);
    }

}