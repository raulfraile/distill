<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$distill = new Distill();
$distill->extract(__DIR__ . '/../tests/Resources/files/file_ok.tar.gz', __DIR__ . '/extract');
