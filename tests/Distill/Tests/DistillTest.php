<?php

namespace Distill\Tests;

use Distill\Chooser;
use Distill\Distill;
use Distill\Format;
use Distill\FormatGuesser;
use Distill\Strategy\MinimumSize;
use Distill\Strategy\UncompressionSpeed;

use \Mockery as m;

class DistillTest extends TestCase
{

    /**
     * @var Distill
     */
    protected $distill;


    public function setUp()
    {
        $this->distill = new Distill();
        parent::setUp();
    }

    public function testChooserIsCreatedProperly()
    {
        $this->assertInstanceOf('\\Distill\\Chooser', $this->distill->getChooser());
    }

    public function testCanExtractZipFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.zip', $target, new Format\Zip());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractGuessedFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.zip', $target);

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }


}
