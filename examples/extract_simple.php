<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

/*$distill = new Distill();
$distill->extract(__DIR__ . '/../tests/Resources/files/file_ok.tar.gz', __DIR__ . '/extract');
*/

$m = new \Distill\Method\Native\GzipExtractor();
//$m->extract(__DIR__ . '/../tests/Resources/files/file_ok.gz', 'extract', new \Distill\Format\Simple\Gz());

$m->extract(__DIR__ . '/crafted.gz', 'extract', new \Distill\Format\Simple\Gz());
