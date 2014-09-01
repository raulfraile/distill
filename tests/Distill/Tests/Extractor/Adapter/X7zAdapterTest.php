<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\X7zAdapter;
use Distill\File;
use Distill\Format\X7z;

class X7zAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrect7ZFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new X7zAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.7z', new X7z()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testExtractFake7ZFileWith7zCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new X7zAdapter(array(
            array('self', 'extract7zCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_fake.7z', new X7z()), $target);
        $this->assertFalse($response);

        $this->clearTemporaryPath();
    }

}
