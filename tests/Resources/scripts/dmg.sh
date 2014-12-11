#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.dmg $FILES_DIR/file_fake.dmg

################################################################################
# Generate files
################################################################################

# dmg: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.dmg bs=1 count=1240

# dmg: regular file
cd $FILES_DIR/uncompressed
hdiutil create ../file_ok.dmg -volname "test" -srcfolder .