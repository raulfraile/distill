<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$extractor = new Distill();

$extractor->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.zip');
$extractor->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.tgz');

@mkdir(__DIR__ . '/download');
$extractor->downloadPreferredFileAndExtract(__DIR__ . '/download');