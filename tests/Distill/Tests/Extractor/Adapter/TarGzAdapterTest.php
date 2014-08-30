<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\TarGzAdapter;
use Distill\File;
use Distill\Format\TarGz;

class TarGzAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectTarGzFileWithTarCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarGzAdapter(array(
            array('self', 'extractTarCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar.gz', new TarGz()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarGzFileWithArchiveTar()
    {
        if (!class_exists('\\Archive_Tar')) {
            $this->markTestSkipped('Archive_Tar not installed');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarGzAdapter(array(
            array('self', 'extractArchiveTar')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar.gz', new TarGz()), $target);
        $this->assertTrue($response);

        //$this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

}
