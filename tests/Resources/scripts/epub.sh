#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.epub $FILES_DIR/file_fake.epub

################################################################################
# Generate files
################################################################################

# epub: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.epub bs=1 count=1240

# epub: regular file
cd $DIR/templates
zip -X -0 gbe.epub.zip mimetype
zip -X -9 -r gbe.epub.zip * -x mimetype
mv gbe.epub.zip $FILES_DIR/file_ok.epub
rm mimetype