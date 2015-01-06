<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

/*$distill = new Distill();
$distill->extract(__DIR__ . '/../tests/Resources/files/file_ok.tar.gz', __DIR__ . '/extract');
*/

$m = new \Distill\Method\Native\GzipExtractor();
$m->extract(__DIR__ . '/../tests/Resources/files/file_ok.gz', 'extract', new \Distill\Format\Simple\Gz());

//$m->extract(__DIR__ . '/Symfony_Standard_Vendors_2.6.1.tgz', 'extract', new \Distill\Format\Simple\Gz());
//$m->extract(__DIR__ . '/test.txt.gz', 'extract', new \Distill\Format\Simple\Gz());
