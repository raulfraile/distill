<?php

require __DIR__ . '/vendor/autoload.php';

use Distill\Distill;
use Distill\File;

use Distill\Format;

$extractor = new Distill(new \Distill\Extractor\Extractor());

$extractor->addFile(new File(__DIR__ . '/Symfony_Standard_Vendors_2.5.3.tgz', new Format\TarGz()));
$extractor->addFile(new File(__DIR__ . '/Symfony_Standard_Vendors_2.5.3.zip', new Format\Zip()));

$extractor->downloadAndExtract(__DIR__);