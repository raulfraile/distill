<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\TarAdapter;
use Distill\File;
use Distill\Format\Tar;

class TarAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectTarFileWithTarCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarAdapter(array(
            array('self', 'extractTarCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar', new Tar()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarFileWithArchiveTar()
    {
        if (!class_exists('\Archive_Tar')) {
            $this->markTestSkipped('Archive_Tar not installed');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarAdapter(array(
            array('self', 'extractArchiveTar')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar', new Tar()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarFileWithPharData()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarAdapter(array(
            array('self', 'extractPharData')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar', new Tar()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

}
