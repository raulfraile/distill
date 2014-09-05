<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\CabAdapter;
use Distill\File;
use Distill\Format\Cab;

class CabAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectCabFileWithCabextractCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new CabAdapter(array(
            array('self', 'extractCabextractCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.cab', new Cab()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectCabFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new CabAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.cab', new Cab()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFakeCabFileWithCabextractCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new CabAdapter(array(
            array('self', 'extractCabextractCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.cab', new Cab()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

    public function testExtractFakeCabFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new CabAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.cab', new Cab()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

}
