<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\Bz2Adapter;
use Distill\File;
use Distill\Format\Bz2;

class Bz2AdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectGzFileWithBzip2Command()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new Bz2Adapter(array(
            array('self', 'extractBzip2Command')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.bz2', new Bz2()), $target);
        $this->assertTrue($response);

        $this->clearTemporaryPath();
    }

}
