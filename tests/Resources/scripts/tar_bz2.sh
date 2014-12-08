#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.tar.bz2 $FILES_DIR/file_ok_dir.tar.bz2 $FILES_DIR/file_fake.tar.bz2

################################################################################
# Generate files
################################################################################

# tar.bz2: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.tar.bz2 bs=1 count=1240

# tar.bz2: regular file
cd $FILES_DIR/uncompressed
tar -jcvf ../file_ok.tar.bz2 *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.tar.bz2.key

# tar.bz2: single directory
cd ..
rm -f ../file_ok_dir.tar.bz2
tar -jcvf file_ok_dir.tar.bz2 uncompressed/*
printf "/uncompressed/1.txt|1.txt file\n/uncompressed/2.txt|2.txt file\n/uncompressed/3.txt|3.txt file\n/uncompressed/level2/4.txt|4.txt file\n/uncompressed/level2\level3\5.txt|5.txt file" > file_ok_dir.tar.bz2.key