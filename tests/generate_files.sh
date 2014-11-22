#!/bin/bash

rm -fr files/uncompressed
mkdir files/uncompressed

cd files/uncompressed

echo '1.txt file' > 1.txt
echo '2.txt file' > 2.txt
echo '3.txt file' > 3.txt

# zip file
rm -f ../file_ok.zip
zip -j -T -r ../file_ok.zip *.txt

# zip file (single dir)
cd ..
rm -f file_ok_dir.zip
zip -T file_ok_dir.zip uncompressed/*.txt
cd uncompressed

# zip encrypted file
rm -f ../file_encrypted_ok.zip
zip -j -r -e -P 123456 ../file_encrypted_ok.zip *.txt

# tar file
rm -f ../file_ok.tar
tar -cvf ../file_ok.tar *.txt

# tar.gz file
rm -f ../file_ok.tar.gz
tar -zcvf ../file_ok.tar.gz *.txt

# tar.gz file (single dir)
cd ..
rm -f ../file_ok_dir.tar.gz
tar -zcvf file_ok_dir.tar.gz uncompressed/*.txt
cd uncompressed

# tar.bz2 file
rm -f ../file_ok.tar.bz2
tar -jcvf ../file_ok.tar.bz2 *.txt

# tar.xz file
rm -f ../file_ok.tar.xz
tar -Jcvf ../file_ok.tar.xz *.txt

# gz file
rm -f ../file_ok.gz
gzip -c *.txt >> ../file_ok.gz

# xz file
rm -f ../file_ok.xz
xz -z -c *.txt >> ../file_ok.xz

# bz2 file
rm -f ../file_ok.bz2
bzip2 -z -c *.txt >> ../file_ok.bz2

# 7z file
rm -f ../file_ok.7z
7za a -t7z ../file_ok.7z *.txt

# rar file
rm -f ../file_ok.rar
rar a ../file_ok.rar *.txt

# phar file
rm -f ../file_ok.phar
php ../../generate_phar.php ../file_ok.phar 1.txt 2.txt 3.txt

# cab file
rm -f ../file_ok.cab
lcab 1.txt 2.txt 3.txt ../file_ok.cab

# epub file
rm -f ../file_ok.epub
cd ../epub
zip -X -0 gbe.epub.zip mimetype
zip -X -9 -r gbe.epub.zip * -x mimetype
mv gbe.epub.zip ../file_ok.epub
