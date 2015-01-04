#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.gz $FILES_DIR/file_fake.gz

################################################################################
# Generate files
################################################################################

# gz: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.gz bs=1 count=1240

# gz: regular file
cd $FILES_DIR/uncompressed
#gzip -c 1.txt >> ../file_ok.gz
gzip -k 1.txt
mv 1.txt.gz ../file_ok.gz
printf "/1.txt|1.txt file" > ../file_ok.gz.key

gzip -1 -k 1.txt
mv 1.txt.gz ../file_ok_fast.gz
printf "/1.txt|1.txt file" > ../file_ok_fast.gz.key