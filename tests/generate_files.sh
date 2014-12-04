#!/bin/bash

rm -fr files/uncompressed
mkdir files/uncompressed

cd files/uncompressed

echo '1.txt file' > 1.txt
echo '2.txt file' > 2.txt
echo '3.txt file' > 3.txt

# zip file
rm -f ../file_ok.zip ../file_fake.zip
zip -j -T -r ../file_ok.zip *.txt
dd if=/dev/urandom of=../file_fake.zip bs=1 count=1240

# zip file (single dir)
cd ..
rm -f file_ok_dir.zip
zip -T file_ok_dir.zip uncompressed/*.txt
cd uncompressed

# zip encrypted file
rm -f ../file_encrypted_ok.zip
zip -j -r -e -P 123456 ../file_encrypted_ok.zip *.txt

# tar file
rm -f ../file_ok.tar ../file_fake.tar
tar -cvf ../file_ok.tar *.txt
dd if=/dev/urandom of=../file_fake.tar bs=1 count=1240

# tar.gz file
rm -f ../file_ok.tar.gz ../file_fake.tar.gz
tar -zcvf ../file_ok.tar.gz *.txt
dd if=/dev/urandom of=../file_fake.tar.gz bs=1 count=1240

# tar.gz file (single dir)
cd ..
rm -f ../file_ok_dir.tar.gz
tar -zcvf file_ok_dir.tar.gz uncompressed/*.txt
cd uncompressed

# tar.bz2 file
rm -f ../file_ok.tar.bz2 ../file_fake.tar.bz2
tar -jcvf ../file_ok.tar.bz2 *.txt
dd if=/dev/urandom of=../file_fake.tar.bz2 bs=1 count=1240

# tar.xz file
rm -f ../file_ok.tar.xz ../file_fake.tar.xz
tar -Jcvf ../file_ok.tar.xz *.txt
dd if=/dev/urandom of=../file_fake.tar.xz bs=1 count=1240

# gz file
rm -f ../file_ok.gz ../file_fake.gz
gzip -c *.txt >> ../file_ok.gz
dd if=/dev/urandom of=../file_fake.gz bs=1 count=1240

# xz file
rm -f ../file_ok.xz ../file_fake.xz
xz -z -c *.txt >> ../file_ok.xz
dd if=/dev/urandom of=../file_fake.xz bs=1 count=1240

# bz2 file
rm -f ../file_ok.bz2 ../file_fake.bz2
bzip2 -z -c *.txt >> ../file_ok.bz2
dd if=/dev/urandom of=../file_fake.bz2 bs=1 count=1240

# 7z file
rm -f ../file_ok.7z ../file_fake.7z
7za a -t7z ../file_ok.7z *.txt
dd if=/dev/urandom of=../file_fake.7z bs=1 count=1240

# rar file
rm -f ../file_ok.rar ../file_fake.rar
rar a ../file_ok.rar *.txt
dd if=/dev/urandom of=../file_fake.rar bs=1 count=1240

# phar file
rm -f ../file_ok.phar ../file_fake.phar
php ../../generate_phar.php ../file_ok.phar 1.txt 2.txt 3.txt
dd if=/dev/urandom of=../file_fake.phar bs=1 count=1240

# cab file
rm -f ../file_ok.cab ../file_fake.cab
gcab -c ../file_ok.cab 1.txt 2.txt 3.txt
dd if=/dev/urandom of=../file_fake.cab bs=1 count=1240

# epub file
rm -f ../file_ok.epub ../file_fake.epub
cd ../epub
zip -X -0 gbe.epub.zip mimetype
zip -X -9 -r gbe.epub.zip * -x mimetype
mv gbe.epub.zip ../file_ok.epub
dd if=/dev/urandom of=../file_fake.epub bs=1 count=1240
