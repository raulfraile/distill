<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\RarAdapter;
use Distill\File;
use Distill\Format\Rar;

class RarAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectRarFileWithUnrarCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new RarAdapter(array(
            array('self', 'extractUnrarCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.rar', new Rar()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractCorrectRarFileWithRarExtension()
    {
        if (!extension_loaded('rar')) {
            $this->markTestSkipped('rar extension not installed');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new RarAdapter(array(
            array('self', 'extractRarExtension')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.rar', new Rar()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

}
