<?php

namespace Distill\Strategy;

use Distill\File;

interface StrategyInterface
{

    public function getName();

    /**
     * @param File[] $files
     * @return mixed
     */
    public function getPreferredFile(array $files);

}
