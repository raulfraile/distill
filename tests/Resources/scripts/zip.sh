#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.zip $FILES_DIR/file_ok_dir.zip $FILES_DIR/file_fake.zip

################################################################################
# Generate files
################################################################################

# zip: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.zip bs=1 count=1240

# zip: regular file
cd $FILES_DIR/uncompressed
zip -T -r ../file_ok.zip *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.zip.key

# zip: single dir file
cd ..
rm -f file_ok_dir.zip
zip -T -r file_ok_dir.zip uncompressed/*
printf "/uncompressed/1.txt|1.txt file\n/uncompressed/2.txt|2.txt file\n/uncompressed/3.txt|3.txt file\n/uncompressed/level2/4.txt|4.txt file\n/uncompressed/level2/level3/5.txt|5.txt file" > file_ok_dir.zip.key
cd uncompressed

# zip: encrypted file
cd $FILES_DIR/uncompressed
rm -f ../file_encrypted_ok.zip
zip -r -e -P 123456 ../file_ok_encrypted.zip *
printf "1.txt|1.txt file\n2.txt|2.txt file\n3.txt|3.txt file\nlevel2/4.txt|4.txt file\nlevel2/level3/5.txt|5.txt file" > ../file_ok_encrypted.zip.key
