<?php

namespace Distill;

use Distill\Extractor\ExtractorInterface;
use Distill\Strategy\MinimumSize;
use Distill\Strategy\StrategyInterface;

class Distill
{

    /**
     * Compressed file extractor.
     * @var ExtractorInterface Extractor
     */
    protected $extractor;

    /**
     *
     * @var StrategyInterface
     */
    protected $strategy;

    protected $files;


    /**
     * Constructor.
     * @param ExtractorInterface $extractor
     * @param StrategyInterface $strategy
     */
    public function __construct(ExtractorInterface $extractor, StrategyInterface $strategy = null)
    {
        $this->extractor = $extractor;

        if (null === $strategy) {
            $strategy = new MinimumSize();
        }

        $this->strategy = $strategy;
        $this->files = array();
    }

    /**
     * Adds a new file.
     * @param File $file
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;
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