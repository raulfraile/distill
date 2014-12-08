#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.7z $FILES_DIR/file_ok_fake.7z

################################################################################
# Generate files
################################################################################

# 7z: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.7z bs=1 count=1240

# 7z: regular file
cd $FILES_DIR/uncompressed
7za a -t7z ../file_ok.7z *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.7z.key