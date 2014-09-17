<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;

$distill = new Distill();

$preferredFile = $distill
    ->getChooser()
    ->setStrategy(new \Distill\Strategy\UncompressionSpeed())
    ->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.zip')
    ->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.tgz')
    ->getPreferredFile();

echo $preferredFile . \PHP_EOL;