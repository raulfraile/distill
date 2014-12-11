#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.iso $FILES_DIR/file_fake.iso

################################################################################
# Generate files
################################################################################

# iso: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.iso bs=1 count=1240

# iso: regular file
cd $FILES_DIR/uncompressed
hdiutil makehybrid -iso -joliet -o ../file_ok.iso .