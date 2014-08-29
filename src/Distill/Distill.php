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
        usort($this->files, 'self::order');

        if (empty($this->files)) {
            return null;
        }

        return $this->files[0];
    }

    /**
     * Order files based on the strategy.
     * @param File $file1 File 1
     * @param File $file2 File 2
     *
     * @return int
     */
    protected function order(File $file1, File $file2)
    {
        $priority1 = $file1->getFormat()->getPriority($this->strategy);
        $priority2 = $file2->getFormat()->getPriority($this->strategy);

        if ($priority1 == $priority2) {
            return 0;
        }

        return ($priority1 > $priority2) ? -1 : 1;
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