<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\TarBz2Adapter;
use Distill\File;
use Distill\Format\TarBz2;

class TarBz2AdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectTarBz2FileWithTarCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarBz2Adapter(array(
            array('self', 'extractTarCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar.bz2', new TarBz2()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectTarBz2FileWithArchiveTar()
    {
        if (!class_exists('\Archive_Tar')) {
            $this->markTestSkipped('Archive_Tar not installed');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarBz2Adapter(array(
            array('self', 'extractArchiveTar')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar.bz2', new TarBz2()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

}
