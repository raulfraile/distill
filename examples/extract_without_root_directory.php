<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$distill = new Distill();
$distill->extractWithoutRootDirectory(__DIR__ . '/../tests/files/file_ok_dir.tar.gz', __DIR__ . '/extract');
