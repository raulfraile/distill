<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;
use Distill\File;
use Distill\Format\Zip;

$extractor = new Distill();

$extractor->extract2(__DIR__ . '/../tests/files/file_ok.zip', __DIR__ . '/extract');
/*
$file = new File(__DIR__ . '/../tests/files/file_ok.zip', new Zip());

$extractor->extract($file, __DIR__ . '/extract');






$extractor = new Distill();

$extractor->extract(__DIR__ . '/../tests/files/file_ok.zip', __DIR__ . '/extract');

*/