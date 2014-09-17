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

/**
 * Strategy interface.
 *
 * Any strategy must implement this interface.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
interface StrategyInterface
{

    /**
     * Gets the strategy name.
     *
     * @return string Strategy name
     */
    public function getName();

    /**
     * Returns the preferred file.
     * @param File[] $files
     *
     * @return null|File
     */
    public function getPreferredFile(array $files);

}
