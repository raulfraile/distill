<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\XzAdapter;
use Distill\File;
use Distill\Format\Xz;

class XzAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectXzFileWithXzCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new XzAdapter(array(
            array('self', 'extractXzCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.xz', new Xz()), $target);
        $this->assertTrue($response);

        //$this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectXzFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new XzAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.xz', new Xz()), $target);
        $this->assertTrue($response);

        $this->clearTemporaryPath();
    }

    public function testExtractFakeXzFileWithXzCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new XzAdapter(array(
            array('self', 'extractXzCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.xz', new Xz()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

    public function testExtractFakeXzFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new XzAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.xz', new Xz()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

}
