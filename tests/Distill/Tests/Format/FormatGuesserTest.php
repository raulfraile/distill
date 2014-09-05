<?php

namespace Distill\Tests\Format;

use Distill\File;
use Distill\Format\Zip;
use Distill\Format\FormatGuesser;

class FormatGuesserTest extends \PHPUnit_Framework_TestCase
{

    /** @var FormatGuesser $formatGuesser */
    protected $formatGuesser;

    protected $filesPath;

    public function setUp()
    {
        $this->formatGuesser = new FormatGuesser();
        $this->filesPath = __DIR__ . '/../../../../files/';
    }

    protected function guessFormat($file, $formatClass)
    {
        $format = $this->formatGuesser->guess($file);

        $this->assertInstanceOf($formatClass, $format);
    }

    public function testBzip2FileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.bz2', '\\Distill\\Format\\Bz2');
        $this->guessFormat($this->filesPath . 'file_ok.bz', '\\Distill\\Format\\Bz2');
        $this->guessFormat($this->filesPath . 'file_ok.BZ2', '\\Distill\\Format\\Bz2');
    }

    public function testGzipFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.gz', '\\Distill\\Format\\Gz');
    }

    public function testPharFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.phar', '\\Distill\\Format\\Phar');
    }

    public function testRarFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.rar', '\\Distill\\Format\\Rar');
    }

    public function testTarFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.tar', '\\Distill\\Format\\Tar');
    }

    public function testTarBz2FileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.tar.bz2', '\\Distill\\Format\\TarBz2');
    }

    public function testTarGzFileGuesser()
    {
        $this->guessFormat('test.tar.gz', '\\Distill\\Format\\TarGz');
        $this->guessFormat('test.tgz', '\\Distill\\Format\\TarGz');
    }

    public function testTarXzFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.tar.xz', '\\Distill\\Format\\TarXz');
    }

    public function testTar7zFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.7z', '\\Distill\\Format\\X7z');
    }

    public function testXzFileGuesser()
    {
        $this->guessFormat('test.xz', '\\Distill\\Format\\Xz');
    }

    public function testZipFileGuesser()
    {
        $this->guessFormat($this->filesPath . 'file_ok.zip', '\\Distill\\Format\\Zip');
    }

    public function testUnknownFileGuesser()
    {
        $this->setExpectedException('Distill\\Exception\\ExtensionNotSupportedException');
        $this->formatGuesser->guess($this->filesPath . 'empty.txt');
    }

}
