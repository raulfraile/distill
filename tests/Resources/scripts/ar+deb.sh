#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.ar $FILES_DIR/file_fake.ar
rm -f $FILES_DIR/file_ok.deb $FILES_DIR/file_fake.deb

################################################################################
# Generate files
################################################################################

# ar: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.ar bs=1 count=1240
dd if=/dev/urandom of=$FILES_DIR/file_fake.deb bs=1 count=1240

# ar: regular file
cd $FILES_DIR/uncompressed
ar rc ../file_ok.ar 1.txt 2.txt 3.txt
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file" > ../file_ok.ar.key
cp ../file_ok.ar ../file_ok.deb
cp ../file_ok.ar.key ../file_ok.deb.key