<?php

namespace Distill\Tests;

use Distill\Format;
use Distill\FormatGuesser;

class FormatGuesserTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormatGuesser $formatGuesser */
    protected $formatGuesser;

    protected $filesPath;

    public function setUp()
    {
        $this->formatGuesser = new FormatGuesser([
            new Format\Simple\Bz2(),
            new Format\Simple\Cab(),
            new Format\Simple\Gz(),
            new Format\Simple\Phar(),
            new Format\Simple\Rar(),
            new Format\Simple\Tar(),
            new Format\Composed\TarBz2(),
            new Format\Composed\TarGz(),
            new Format\Composed\TarXz(),
            new Format\Simple\X7z(),
            new Format\Simple\Xz(),
            new Format\Simple\Zip(),
        ]);
        $this->filesPath = __DIR__.'/../../../../files/';
    }

    protected function guessFormat($file, $formatClass)
    {
        $formatChain = $this->formatGuesser->guess($file);

        $this->assertInstanceOf($formatClass, $formatChain[0]);
    }

    protected function guessFormatChain($file, array $formatClasses)
    {
        $formatChain = $this->formatGuesser->guess($file);

        $this->assertEquals(count($formatChain), count($formatClasses));

        for ($i = 0, $total = count($formatChain); $i < $total; $i++) {
            $this->assertInstanceOf($formatClasses[$i], $formatChain[$i]);
        }
    }

    public function testBzip2FileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.bz2', '\\Distill\\Format\\Simple\\Bz2');
        $this->guessFormat($this->filesPath.'file_ok.bz', '\\Distill\\Format\\Simple\\Bz2');
        $this->guessFormat($this->filesPath.'file_ok.BZ2', '\\Distill\\Format\\Simple\\Bz2');
    }

    public function testGzipFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.gz', '\\Distill\\Format\\Simple\\Gz');
    }

    public function testPharFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.phar', '\\Distill\\Format\\Simple\\Phar');
    }

    public function testRarFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.rar', '\\Distill\\Format\\Simple\\Rar');
    }

    public function testTarFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.tar', '\\Distill\\Format\\Simple\\Tar');
    }

    public function testTarBz2FileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.tar.bz2', '\\Distill\\Format\\Composed\\TarBz2');
    }

    public function testTarGzFileGuesser()
    {
        $this->guessFormat('test.tar.gz', '\\Distill\\Format\\Composed\\TarGz');
        $this->guessFormat('test.tgz', '\\Distill\\Format\\Composed\\TarGz');
    }

    public function testTarXzFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.tar.xz', '\\Distill\\Format\\Composed\\TarXz');
    }

    public function testTar7zFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.7z', '\\Distill\\Format\\Simple\\x7z');
    }

    public function testXzFileGuesser()
    {
        $this->guessFormat('test.xz', '\\Distill\\Format\\Simple\\Xz');
    }

    public function testZipFileGuesser()
    {
        $this->guessFormat($this->filesPath.'file_ok.zip', '\\Distill\\Format\\Simple\\Zip');
    }

    public function testUnknownFileGuesser()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileUnknownFormatException');
        $this->formatGuesser->guess($this->filesPath.'empty.txt');
    }

    public function testFileComposedExtensionGuesser()
    {
        $this->guessFormatChain('test.txt.gz', ['\\Distill\\Format\\Simple\\Gz']);
        $this->guessFormatChain('test.zip.gz', ['\\Distill\\Format\\Simple\\Gz', '\\Distill\\Format\\Simple\\Zip']);
    }
}
