<?php

namespace Distill\Tests;

use Distill\Distill;
use Distill\Format;
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

    public function testCanExtractBz2Files()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.bz2', $target, new Format\Bz2());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
    }

    public function testCanExtractCabFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.cab', $target, new Format\Cab());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractGzFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.gz', $target, new Format\Gz());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
    }

    public function testCanExtractPharFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.phar', $target, new Format\Phar());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractRarFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.rar', $target, new Format\Rar());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar', $target, new Format\Tar());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarBz2Files()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar.bz2', $target, new Format\TarBz2());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarGzFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar.gz', $target, new Format\TarGz());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarXzFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar.xz', $target, new Format\TarXz());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractX7zFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.7z', $target, new Format\X7z());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractXzFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.xz', $target, new Format\Xz());

        $this->assertTrue($response);
        $this->clearTemporaryPath();
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

    public function testCanExtractWithoutRootDirectorySingleDirectoryZipFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extractWithoutRootDirectory($this->filesPath . 'file_ok_dir.zip', $target, new Format\Zip());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractWithoutRootDirectorySingleDirectoryTarGzFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extractWithoutRootDirectory($this->filesPath . 'file_ok_dir.tar.gz', $target, new Format\TarGz());

        $this->assertTrue($response);
        $this->checkDirectoryFiles($target, $this->filesPath . '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCannotExtractWithoutRootDirectoryNoDirectoryZipFiles()
    {
        $this->setExpectedException('Distill\\Exception\\NotSingleDirectoryException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->distill->extractWithoutRootDirectory($this->filesPath . 'file_ok.zip', $target, new Format\Zip());
    }
}
