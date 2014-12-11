#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

mkdir $DIR/sample
echo 'sample file' > $DIR/sample/sample.txt

bzip2 -z -c $DIR/sample/sample.txt >> $DIR/src/Format/Samples/file.bz2
gcab -c $DIR/src/Format/Samples/file.cab $DIR/sample/sample.txt
gzip -c $DIR/sample/sample.txt >> $DIR/src/Format/Samples/file.gz
xz -z -c $DIR/sample/sample.txt >> $DIR/src/Format/Samples/file.xz
rar a $DIR/src/Format/Samples/file.rar $DIR/sample/sample.txt
zip -T $DIR/src/Format/Samples/file.zip $DIR/sample/sample.txt
hdiutil create $DIR/src/Format/Samples/file.dmg -volname "test" -srcfolder $DIR/sample/
hdiutil makehybrid -iso -joliet -o $DIR/src/Format/Samples/file.iso $DIR/sample/

rm -rf $DIR/sample