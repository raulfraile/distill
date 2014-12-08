#!/bin/bash

################################################################################
# Initial configuration
################################################################################
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILES_DIR="$DIR/../files"

################################################################################
# Clean files
################################################################################
rm -f $FILES_DIR/file_ok.jar $FILES_DIR/file_fake.jar

################################################################################
# Generate files
################################################################################

# jar: fake file
dd if=/dev/urandom of=$FILES_DIR/file_fake.jar bs=1 count=1240

# jar: regular file
cd $DIR/../templates/jar
jar cmf META-INF/MANIFEST.MF $FILES_DIR/file_ok.jar *
printf "/1.txt|1.txt file\n/2.txt|2.txt file\n/META-INF/MANIFEST.MF|Manifest-Version: 1.0__nl__Created-By: 1.8.0_25 (Oracle Corporation)__nl__" > $FILES_DIR/file_ok.jar.key