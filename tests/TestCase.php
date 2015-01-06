<?php

namespace Distill\Tests;

use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Test files path
     * @var string
     */
    protected $filesPath;

    /**
     * @var FormatInterface[]
     */
    protected $allFormats;

    /**
     * @var MethodInterface[]
     */
    protected $allMethods;

    protected $temporaryPath;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function setUp()
    {
        $this->filesPath = __DIR__ . '/Resources/files/';

        // formats
        $this->allFormats = [];

        $finder = new Finder();
        $finder
            ->files()
            ->in(__DIR__ . '/../src/Format')
            ->name('*.php')
            ->depth('== 1');

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */

            $className = 'Distill\\Format\\' . str_replace('/', '\\', preg_replace('/\.php$/', '', $file->getRelativePathname()));

            $this->allFormats[] = new $className();
        }

        // methods
        $this->allMethods = [];

        $finder = new Finder();
        $finder
            ->files()
            ->in(__DIR__ . '/../src/Method')
            ->name('*.php')
            ->notName('Abstract*')
            ->notName('*Interface*')
            ->notPath('/.*\/GzipExtractor\/.*$/')
            ->depth('>= 1');

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */

            $className = 'Distill\\Method\\' . str_replace('/', '\\', preg_replace('/\.php$/', '', $file->getRelativePathname()));

            $this->allMethods[] = new $className();
        }

        $this->filesystem = new Filesystem();

        $this->temporaryPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();

    }

    public function tearDown()
    {
        $this->filesystem = new Filesystem();
        if ($this->filesystem->exists($this->temporaryPath)) {
            $this->filesystem->remove($this->temporaryPath);
        }
    }

    protected function getTemporaryPath()
    {
        return $this->temporaryPath;
    }

    protected function clearTemporaryPath()
    {
        $this->filesystem->remove($this->temporaryPath);
    }

    protected function checkDirectoryFiles($origin, $target)
    {
        $this->assertEquals($this->getDirectoryHash($origin), $this->getDirectoryHash($target));
    }

    protected function getDirectoryHash($directory)
    {
        $files = [];

        $directoryIterator = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS);

        $objects = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {
            /** @var \SplFileInfo $object */
            $key = preg_replace('#^'.preg_quote($directory).'#', '', $object->getPathName());

            $files[$key] = $object->getRealPath();
        }

        ksort($files);

        $hash = hash_init('sha512');
        foreach ($files as $fileRelativePath => $fileFullPath) {
            hash_update($hash, $fileRelativePath.file_get_contents($fileFullPath));
        }

        return hash_final($hash);
    }

    protected function assertUncompressed($directory, $originalFile, $isSingleFile = false, $prefixRemove = '')
    {
        self::assertThat(
            self::compareUncompressed($directory, $originalFile, $isSingleFile, $prefixRemove),
            new \PHPUnit_Framework_Constraint_IsTrue(),
            'uncompressed fail '.$originalFile
        );
    }

    protected function compareUncompressed($directory, $originalFile, $isSingleFile = false, $prefixRemove = '')
    {
        if (false === file_exists($this->filesPath.$originalFile.'.key')) {
            return true;
        }

        $keys = [];
        $lines = explode("\n", file_get_contents($this->filesPath.$originalFile.'.key'));
        foreach ($lines as $line) {
            $lineParts = explode('|', $line);

            $key = preg_replace('#^'.preg_quote($prefixRemove).'#', '', $lineParts[0]);
            $content = str_replace('__nl__', "\n", $lineParts[1]);

            if ($content != '') {
                $content .= "\n";
            }

            $keys[$key] = $content;
        }

        if (empty($keys)) {
            return false;
        }

        $directoryIterator = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS);

        $objects = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $name => $object) {
            /** @var \SplFileInfo $object */

            if ($object->isDir()) {
                continue;
            }

            if ($isSingleFile) {
                return implode('', $keys) === file_get_contents($object->getRealPath());
            }

            $key = preg_replace('#^'.preg_quote($directory).'#', '', $object->getPathName());

            if (false === array_key_exists($key, $keys)) {
                return false;
            }

            $contents = file_get_contents($object->getRealPath());
            $contents = str_replace("\r\n", "\n", $contents);

            if ($keys[$key] != $contents) {
                return false;
            }

            unset($keys[$key]);
        }

        return empty($keys);
    }
}
