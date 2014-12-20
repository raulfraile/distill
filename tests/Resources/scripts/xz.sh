#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.xz $FILES_DIR/file_fake.xz $FILES_DIR/file_ok.zip.xz $FILES_DIR/file_ok.zip.xz.xz

################################################################################
# Generate files
################################################################################

# xz: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.xz bs=1 count=1240

# xz: regular file
cd $FILES_DIR/uncompressed
xz -z -c 1.txt >> ../file_ok.xz
printf "/file_ok|1.txt file" > ../file_ok.xz.key

cd $FILES_DIR/uncompressed
xz -z -c ../file_ok.zip >> ../file_ok.zip.xz
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.zip.xz.key
xz -z -c ../file_ok.zip.xz >> ../file_ok.zip.xz.xz
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.zip.xz.xz.key