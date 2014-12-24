<?php

namespace Distill\Tests\Method;

use Distill\Exception\IO\Input\FileCorruptedException;
use Distill\Exception\Method\FormatNotSupportedInMethodException;
use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Distill\Tests\TestCase;
use Symfony\Component\Process\Process;
use \Mockery as m;

abstract class AbstractMethodTest extends TestCase
{
    /** @var MethodInterface $method */
    protected $method;

    protected $supportedFormats = [];
    protected $unsupportedFormats = [];
    protected $validResources = ['file_ok'];

    public function setUp()
    {
        parent::setUp();

        $this->supportedFormats = [];
        $this->unsupportedFormats = [];

        foreach ($this->allFormats as $format) {
            if ($this->method->isFormatSupported($format)) {
                $this->supportedFormats[] = $format;
            } else {
                $this->unsupportedFormats[] = $format;
            }
        }
    }

    protected function extract($file, $target, FormatInterface $format)
    {
        return $this->method->extract($this->filesPath.$file, $target, $format);
    }


    /**
     * Checks whether the command exists in the system.
     * @param string $command Command to be checked.
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function existsCommand($command)
    {
        if ($this->isWindows()) {
            return false;
        }

        $process = new Process('command -v '.$command.' > /dev/null');
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * Checks whether PHP is running on Windows.
     *
     * @return bool Returns TRUE when running on windows, FALSE otherwise
     */
    protected function isWindows()
    {
        return defined('PHP_WINDOWS_VERSION_BUILD');
    }

    protected function checkFormatUsingMethod(FormatInterface $format)
    {
        /*$this->checkValidFormatUsingMethod($format);
        $this->checkInvalidFormatUsingMethod($format);
        $this->checkNoFormatUsingMethod($format);*/
    }

    public function testAllFormatsSupportedByMethod()
    {
        // check supported formats (ok and invalid data)
        foreach ($this->supportedFormats as $format) {
            foreach ($this->getOkTestResources($format) as $file) {
                $this->checkSupportedValidFormatUsingMethod($file, $format);
            }

            $this->checkSupportedInvalidFormatUsingMethod($format);
        }

        // check unsupported formats
        foreach ($this->unsupportedFormats as $format) {
            $this->checkUnsupportedFormatUsingMethod($format);
        }
    }

    protected function getOkTestResources(FormatInterface $format)
    {
        $ext = $format->getExtensions()[0];
        $files = [];
        foreach ($this->validResources as $base) {
            $filename = $base.'.'.$ext;
            if (file_exists($this->filesPath.$filename)) {
                $files[] = $filename;
            }
        }

        return $files;
    }

    public function checkSupportedValidFormatUsingMethod($file, FormatInterface $format)
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $response = $this->extract($file, $target, $format);

        $this->assertTrue($response);
        $this->assertUncompressed($target, $file);
        $this->clearTemporaryPath();
    }

    public function checkSupportedInvalidFormatUsingMethod(FormatInterface $format)
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        try {
            $this->extract('file_fake.zip', $target, $format);
        } catch (FileCorruptedException $e) {
            $this->assertEquals($this->filesPath.'file_fake.zip', $e->getFilename());
            $this->assertEquals(FileCorruptedException::SEVERITY_HIGH, $e->getSeverity());

            return;
        }

        $errorMessage = sprintf('Expected exception has not been thrown: %s should throw a FileCorruptedException for %s', $this->method->getName(), $format->getName());
        $this->assertFalse(true, $errorMessage);

        $this->clearTemporaryPath();
    }

    public function checkUnsupportedFormatUsingMethod(FormatInterface $format)
    {
        $target = $this->getTemporaryPath();
        $this->clearTemporaryPath();

        $file = 'file_ok.' . $format->getExtensions()[0];

        try {
            $this->extract($file, $target, $format);
        } catch (FormatNotSupportedInMethodException $e) {
            $this->assertEquals($format, $e->getFormat());
            $this->assertEquals($this->method, $e->getMethod());
        }
    }

}
