#!/usr/bin/env php
<?php

$phar = new \Phar($argv[1], 0, basename($argv[1]));

$i = 2;
while ($i < $argc) {
    $phar->addFile($argv[$i]);

    $i++;
}
