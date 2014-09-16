<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;
use Distill\Strategy\MinimumSize;

$distill = new Distill();

$preferredFile = $distill->getChooser()
    ->addFile('test.tar.gz')
    ->addFile('test.zip')
    ->getPreferredFile();

echo $preferredFile . \PHP_EOL;