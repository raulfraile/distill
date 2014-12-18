#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# generate structure
rm -fr $DIR/../files/uncompressed
mkdir $DIR/../files/uncompressed
mkdir $DIR/../files/uncompressed/level2
mkdir $DIR/../files/uncompressed/level2/level3

echo '1.txt file' > $DIR/../files/uncompressed/1.txt
echo '2.txt file' > $DIR/../files/uncompressed/2.txt
echo '3.txt file' > $DIR/../files/uncompressed/3.txt
echo '4.txt file' > $DIR/../files/uncompressed/level2/4.txt
echo '5.txt file' > $DIR/../files/uncompressed/level2/level3/5.txt

$DIR/tar.sh
$DIR/tar_gz.sh
$DIR/tar_bz2.sh
$DIR/tar_xz.sh
$DIR/gz.sh
$DIR/bz2.sh
$DIR/zip.sh
$DIR/xz.sh
$DIR/rar.sh
$DIR/phar.sh
$DIR/7z.sh
$DIR/cab.sh
$DIR/epub.sh
$DIR/jar.sh
$DIR/dmg.sh
$DIR/iso.sh
$DIR/ar+deb.sh
$DIR/cpio.sh
$DIR/shar.sh