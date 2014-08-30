<?php

namespace Distill\Strategy;

use Distill\File;

class MinimumSize implements StrategyInterface
{

    public function getName()
    {
        return 'minimum_size';
    }

    /**
     * @param File[] $files
     * @return mixed
     */
    public function getPreferredFile(array $files)
    {
        usort($files, 'self::order');

        if (empty($files)) {
            return null;
        }

        return $files[0];
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
        $priority1 = $file1->getFormat()->getCompressionRatioLevel();
        $priority2 = $file2->getFormat()->getCompressionRatioLevel();

        if ($priority1 == $priority2) {
            return 0;
        }

        return ($priority1 > $priority2) ? -1 : 1;
    }
}
