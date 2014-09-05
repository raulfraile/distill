<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\GzAdapter;
use Distill\File;
use Distill\Format\Gz;

class GzAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectGzFileWithGzipCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new GzAdapter(array(
            array('self', 'extractGzipCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.gz', new Gz()), $target);
        $this->assertTrue($response);

        $this->clearTemporaryPath();
    }

    public function testExtractCorrectGzFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new GzAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.gz', new Gz()), $target);
        $this->assertTrue($response);

        $this->clearTemporaryPath();
    }

    public function testExtractFakeGzFileWithGzipCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new GzAdapter(array(
            array('self', 'extractGzipCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.gz', new Gz()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

    public function testExtractFakeGzFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new GzAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.gz', new Gz()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

}
