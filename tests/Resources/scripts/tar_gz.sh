#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.tar.gz $FILES_DIR/file_ok_dir.tar.gz $FILES_DIR/file_fake.tar.gz

################################################################################
# Generate files
################################################################################

# tar.gz: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.tar.gz bs=1 count=1240

# tar.gz: regular file
cd $FILES_DIR/uncompressed
tar -zcvf ../file_ok.tar.gz *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.tar.gz.key

# tar.gz: single directory
cd ..
rm -f ../file_ok_dir.tar.gz
tar -zcvf file_ok_dir.tar.gz uncompressed/*
printf "/uncompressed/1.txt|1.txt file\n/uncompressed/2.txt|2.txt file\n/uncompressed/3.txt|3.txt file\n/uncompressed/level2/4.txt|4.txt file\n/uncompressed/level2/level3/5.txt|5.txt file" > file_ok_dir.tar.gz.key