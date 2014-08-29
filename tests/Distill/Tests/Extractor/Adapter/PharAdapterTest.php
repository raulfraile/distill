<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\PharAdapter;
use Distill\File;
use Distill\Format\Phar;

class PharAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectPharFileWithPharExtension()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new PharAdapter(array(
            array('self', 'extractPharExtension')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.phar', new Phar()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

}
