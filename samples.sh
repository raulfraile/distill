#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo 'sample file' > $DIR/sample.txt

bzip2 -z -c $DIR/sample.txt >> $DIR/src/Format/Samples/file.bz2
gcab -c $DIR/src/Format/Samples/file.cab $DIR/sample.txt
gzip -c $DIR/sample.txt >> $DIR/src/Format/Samples/file.gz
xz -z -c $DIR/sample.txt >> $DIR/src/Format/Samples/file.xz
rar a $DIR/src/Format/Samples/file.rar $DIR/sample.txt
zip -T $DIR/src/Format/Samples/file.zip $DIR/sample.txt

rm -f $DIR/sample.txt