<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$distill = new Distill();
$distill->extractIgnoringRootDirectory(__DIR__ . '/../tests/files/file_ok_dir.zip', __DIR__ . '/extract');
