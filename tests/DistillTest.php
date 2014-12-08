<?php

namespace Distill\Tests;

use Distill\Distill;
use Distill\Format;
use Distill\Exception;
use Distill\Method\Command\Cabextract;
use Distill\Method\Command\Gnome\Gcab;
use Distill\Method\Command\x7zip;
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

    public function testCorruptedInputFileThrowsException()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileCorruptedException');

        try {
            $this->distill->extract($this->filesPath . 'file_fake.zip', $this->getTemporaryPath());
        } catch (Exception\IO\Input\FileCorruptedException $e) {
            $this->assertEquals($this->filesPath . 'file_fake.zip', $e->getFilename());
            $this->assertEquals(Exception\IO\Input\FileCorruptedException::SEVERITY_HIGH, $e->getSeverity());
            throw $e;
        }
    }

    public function testNotFoundInputFileThrowsException()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileNotFoundException');

        $notFoundFile = sys_get_temp_dir() . '/' . uniqid();

        try {
            $this->distill->extract($notFoundFile, $this->getTemporaryPath());
        } catch (Exception\IO\Input\FileNotFoundException $e) {
            $this->assertEquals($notFoundFile, $e->getFilename());
            throw $e;
        }
    }

    public function testInputFileNotReadableThrowsException()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileNotReadableException');

        $notReadableFile = sys_get_temp_dir() . '/' . uniqid();
        file_put_contents($notReadableFile, 'test');
        chmod($notReadableFile, 0000);

        try {
            $this->distill->extract($notReadableFile, $this->getTemporaryPath());
        } catch (Exception\IO\Input\FileNotReadableException $e) {
            $this->assertEquals($notReadableFile, $e->getFilename());
            throw $e;
        }
    }

    public function testEmptyInputFileThrowsException()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileEmptyException');

        $emptyFile = sys_get_temp_dir() . '/' . uniqid();
        file_put_contents($emptyFile, '');

        try {
            $this->distill->extract($emptyFile, $this->getTemporaryPath());
        } catch (Exception\IO\Input\FileEmptyException $e) {
            $this->assertEquals($emptyFile, $e->getFilename());
            throw $e;
        }
    }

    public function testUnknownInputFormatThrowsException()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileUnknownFormatException');

        $unknownFormatFile = sys_get_temp_dir() . '/' . uniqid() . '.xxx';
        file_put_contents($unknownFormatFile, 'test');

        try {
            $this->distill->extract($unknownFormatFile, $this->getTemporaryPath());
        } catch (Exception\IO\Input\FileUnknownFormatException $e) {
            $this->assertEquals($unknownFormatFile, $e->getFilename());
            throw $e;
        }
    }

    public function testOutputDirectoryNotWritableThrowsException()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Output\\TargetDirectoryNotWritableException');

        $target = sys_get_temp_dir() . '/' . uniqid();
        mkdir($target, 0000);

        try {
            $this->distill->extract($this->filesPath . 'file_ok.zip', $target);
        } catch (Exception\IO\Output\TargetDirectoryNotWritableException $e) {
            $this->assertEquals($target, $e->getTarget());
            throw $e;
        }
    }

    public function testChooserIsCreatedProperly()
    {
        $this->assertInstanceOf('\\Distill\\Chooser', $this->distill->getChooser());
    }

    public function testCanExtractBz2Files()
    {
        $format = new Format\Bz2();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('bzip2 files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.bz2', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.bz2', true);
        $this->clearTemporaryPath();
    }

    public function testCanExtractCabFiles()
    {
        $format = new Format\Cab();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('cab files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.cab', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.cab');
        $this->clearTemporaryPath();
    }

    public function testCanExtractGzFiles()
    {
        $format = new Format\Gz();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('gzip files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.gz', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.gz', true);
        $this->clearTemporaryPath();
    }

    public function testCanExtractPharFiles()
    {
        $format = new Format\Phar();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('phar files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.phar', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.phar');
        $this->clearTemporaryPath();
    }

    public function testCanExtractRarFiles()
    {
        $format = new Format\Rar();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('rar files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.rar', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.rar');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarFiles()
    {
        $format = new Format\Tar();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('tar files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.tar');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarBz2Files()
    {
        $format = new Format\TarBz2();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('tar.bz2 files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar.bz2', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.tar.bz2');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarGzFiles()
    {
        $format = new Format\TarGz();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('tar.gz files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar.gz', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.tar.gz');
        $this->clearTemporaryPath();
    }

    public function testCanExtractTarXzFiles()
    {
        $format = new Format\TarXz();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('tar.xz files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.tar.xz', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.tar.xz');
        $this->clearTemporaryPath();
    }

    public function testCanExtract7zFiles()
    {
        $format = new Format\x7z();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('7z files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.7z', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.7z');
        $this->clearTemporaryPath();
    }

    public function testCanExtractXzFiles()
    {
        $format = new Format\Xz();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('xz files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.xz', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.xz', true);
        $this->clearTemporaryPath();
    }

    public function testCanExtractZipFiles()
    {
        $format = new Format\Zip();

        if (false === $this->distill->isFormatSupported($format)) {
            $this->markTestSkipped('zip files are not supported');
        }

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.zip', $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.zip');
        $this->clearTemporaryPath();
    }

    public function testCanExtractGuessedFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extract($this->filesPath . 'file_ok.zip', $target);

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok.zip');
        $this->clearTemporaryPath();
    }

    public function testCanExtractWithoutRootDirectorySingleDirectoryZipFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extractWithoutRootDirectory($this->filesPath . 'file_ok_dir.zip', $target, new Format\Zip());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok_dir.zip', false, '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCanExtractWithoutRootDirectorySingleDirectoryTarGzFiles()
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->distill->extractWithoutRootDirectory($this->filesPath . 'file_ok_dir.tar.gz', $target, new Format\TarGz());

        $this->assertTrue($response);
        $this->assertUncompressed($target, 'file_ok_dir.tar.gz', false, '/uncompressed');
        $this->clearTemporaryPath();
    }

    public function testCannotExtractWithoutRootDirectoryNoDirectoryZipFiles()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Output\\NotSingleDirectoryException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        try {
            $this->distill->extractWithoutRootDirectory($this->filesPath . 'file_ok.zip', $target, new Format\Zip());
        } catch (Exception\IO\Output\NotSingleDirectoryException $e) {
            $this->assertEquals($this->filesPath . 'file_ok.zip', $e->getFilename());
            throw $e;
        }
    }

    public function testFormatIsNotSupportedAfterDisablingFormat()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileFormatNotSupportedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        try {
            $this->distill
                ->disableFormat(Format\Zip::getName())
                ->extract($this->filesPath . 'file_ok.zip', $target, new Format\Zip());
        } catch (Exception\IO\Input\FileFormatNotSupportedException $e) {
            $this->assertEquals($this->filesPath . 'file_ok.zip', $e->getFilename());
            $this->assertInstanceOf('Distill\\Format\\Zip', $e->getFormat());
            throw $e;
        }
    }

    public function testFormatNotSupportedAfterDisablingAllMethods()
    {
        $this->setExpectedException('Distill\\Exception\\IO\\Input\\FileFormatNotSupportedException');

        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $this->distill->disableMethod(Cabextract::getName());
        $this->distill->disableMethod(Gcab::getName());
        $this->distill->disableMethod(x7zip::getName());

        $result = $this->distill
            ->extract($this->filesPath . 'file_ok.cab', $target, new Format\Cab());

        $this->clearTemporaryPath();
    }
}
