<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$distill = new Distill();
$distill->extract(__DIR__ . '/../tests/files/file_ok.zip', __DIR__ . '/extract');