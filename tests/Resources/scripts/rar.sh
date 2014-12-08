#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.rar $FILES_DIR/file_fake.rar

################################################################################
# Generate files
################################################################################

# rar: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.rar bs=1 count=1240

# rar: regular file
cd $FILES_DIR/uncompressed
rar a ../file_ok.rar *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.rar.key