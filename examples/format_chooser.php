<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$extractor = new Distill();

$extractor->addFile('test.tar.gz');
$extractor->addFile('test.zip');

$preferredFile = $extractor->getPreferredFile();

echo $preferredFile->getPath() . "\n";