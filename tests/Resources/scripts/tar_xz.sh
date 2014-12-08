#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.tar.xz $FILES_DIR/file_ok_dir.tar.xz $FILES_DIR/file_fake.tar.xz

################################################################################
# Generate files
################################################################################

# tar.xz: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.tar.xz bs=1 count=1240

# tar.xz: regular file
cd $FILES_DIR/uncompressed
tar -Jcvf ../file_ok.tar.xz *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.tar.xz.key

# tar.xz: single directory
cd ..
rm -f ../file_ok_dir.tar.xz
tar -Jcvf file_ok_dir.tar.xz uncompressed/*
printf "/uncompressed/1.txt|1.txt file\n/uncompressed/2.txt|2.txt file\n/uncompressed/3.txt|3.txt file\n/uncompressed/level2/4.txt|4.txt file\n/uncompressed/level2\level3\5.txt|5.txt file" > file_ok_dir.tar.xz.key