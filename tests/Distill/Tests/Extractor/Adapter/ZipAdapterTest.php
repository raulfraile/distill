<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\ZipAdapter;
use Distill\File;
use Distill\Format\Zip;

class ZipAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectZipFileWithUnzipCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new ZipAdapter(array(
            array('self', 'extractUnzipCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.zip', new Zip()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectZipFileWithZipArchive()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new ZipAdapter(array(
            array('self', 'extractZipArchive')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.zip', new Zip()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectZipFileWithPharData()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new ZipAdapter(array(
            array('self', 'extractPharData')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.zip', new Zip()), $target);
        $this->assertTrue($response);

        ld('--------------**-');
        //ldd($target);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');

        $this->clearTemporaryPath();
    }

}
