<?php

namespace Distill\Tests;

use Distill\Extractor\Adapter\TarXzAdapter;
use Distill\File;
use Distill\Format\TarXz;

class TarXzAdapterTest extends AbstractAdapterTest
{

    public function testExtractCorrectTarXzFileWithTarCommand()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->adapter = new TarXzAdapter(array(
            array('self', 'extractTarCommand')
        ));

        $response = $this->adapter->extract(new File($this->filesPath . 'file_ok.tar.xz', new TarXz()), $target);
        $this->assertTrue($response);

        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

}
