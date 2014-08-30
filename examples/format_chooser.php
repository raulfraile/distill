<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$extractor = new Distill();

$extractor->addFile('test.tgz');
$extractor->addFile('test.zip');

$preferredFile = $extractor->getPreferredFile();

echo $preferredFile->getPath();