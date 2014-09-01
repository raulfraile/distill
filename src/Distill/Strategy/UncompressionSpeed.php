<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Strategy;

use Distill\File;

class UncompressionSpeed implements StrategyInterface
{

    public function getName()
    {
        return 'uncompression_speed';
    }

    /**
     * @param  File[] $files
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
        $priority1 = $file1->getFormat()->getUncompressionSpeedLevel();
        $priority2 = $file2->getFormat()->getUncompressionSpeedLevel();

        if ($priority1 == $priority2) {
            return 0;
        }

        return ($priority1 > $priority2) ? -1 : 1;
    }
}
