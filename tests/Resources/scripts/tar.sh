#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.tar $FILES_DIR/file_ok_dir.tar $FILES_DIR/file_fake.tar

################################################################################
# Generate files
################################################################################

# tar: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.tar bs=1 count=1240

# tar: regular file
cd $FILES_DIR/uncompressed
tar -cvf ../file_ok.tar *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/3.txt|3.txt file\n/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok.tar.key

# tar: files in dirs, without dirs
tar -cvf ../file_ok_no_dirs.tar level2/4.txt level2/level3/5.txt
printf "/level2/4.txt|4.txt file\n/level2/level3/5.txt|5.txt file" > ../file_ok_no_dirs.tar.key

# tar: empty files
touch empty.txt
tar -cvf ../file_ok_empty_file.tar empty.txt
printf "/empty.txt|" > ../file_ok_empty_file.tar.key

# tar: links
cd level2
ln -s ../1.txt 1.txt
ln ../2.txt 2.txt
cd ..
tar -cvf file_ok_links.tar 1.txt 2.txt  level2/1.txt level2/2.txt
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/level2/1.txt|1.txt file\n/level2/2.txt|2.txt file" > ../file_ok_links.tar.key
