#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.bz2 $FILES_DIR/file_fake.bz2

################################################################################
# Generate files
################################################################################

# bz2: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.bz2 bs=1 count=1240

# bz2: regular file
cd $FILES_DIR/uncompressed
bzip2 -z -c 1.txt >> ../file_ok.bz2
printf "/file_ok|1.txt file" > ../file_ok.bz2.key