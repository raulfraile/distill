<?php

require __DIR__ . '/../vendor/autoload.php';

use Distill\Distill;
use Distill\Strategy\MinimumSize;

$distill = new Distill();
$strategy = new MinimumSize();

$preferredFile = $distill
    ->getChooser()
    ->setStrategy($strategy)
    ->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.tgz')
    ->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.zip')
    ->getPreferredFile();
echo $preferredFile . \PHP_EOL;

$preferredFile = $distill
    ->getChooser()
    ->setStrategy($strategy)
    ->addFilesWithDifferentExtensions('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3', ['zip', 'tgz'])
    ->getPreferredFile();

echo $preferredFile . \PHP_EOL;
