#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.shar $FILES_DIR/file_fake.shar

################################################################################
# Generate files
################################################################################

# shar: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.shar bs=1 count=1240

# shar: regular file
cd $FILES_DIR/uncompressed
shar 1.txt 2.txt 3.txt > ../file_ok.shar
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file" > ../file_ok.shar.key
