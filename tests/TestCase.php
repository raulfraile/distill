<?php

namespace Distill\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Test files path
     * @var string
     */
    protected $filesPath;

    public function setUp()
    {
        $this->filesPath = __DIR__ . '/Resources/files/';
    }

    protected function getTemporaryPath()
    {
        return sys_get_temp_dir().'/zip';
    }

    protected function clearTemporaryPath()
    {
        exec('rm -fr ' . $this->getTemporaryPath());
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
        foreach($objects as $name => $object){

            /** @var \SplFileInfo $object */
            $key = preg_replace('#^'.preg_quote($directory) . '#', '', $object->getPathName());

            $files[$key] = $object->getRealPath();
        }

        ksort($files);

        $hash = hash_init('sha512');
        foreach ($files as $fileRelativePath => $fileFullPath) {
            hash_update($hash, $fileRelativePath . file_get_contents($fileFullPath));
        }

        return hash_final($hash);
    }

    protected function assertUncompressed($directory, $originalFile, $isSingleFile = false)
    {
        self::assertThat(self::compareUncompressed($directory, $originalFile, $isSingleFile), new \PHPUnit_Framework_Constraint_IsTrue, 'uncompressed fail');
    }

    protected function compareUncompressed($directory, $originalFile, $isSingleFile = false)
    {
        $keys = [];
        $lines = explode("\n", file_get_contents($this->filesPath . $originalFile . '.key'));
        foreach ($lines as $line) {
            $lineParts = explode('|', $line);

            $keys[$lineParts[0]] = $lineParts[1] . "\n";
        }

        if (empty($keys)) {
            return false;
        }

        $directoryIterator = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS);

        $objects = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);

        foreach($objects as $name => $object){

            /** @var \SplFileInfo $object */

            if ($object->isDir()) {
                continue;
            }

            if ($isSingleFile) {
                return implode('', $keys) === file_get_contents($object->getRealPath());
            }

            $key = preg_replace('#^'.preg_quote($directory) . '#', '', $object->getPathName());

            if (false === array_key_exists($key, $keys)) {
                return false;
            }

            if ($keys[$key] != file_get_contents($object->getRealPath())) {
                return false;
            }

            unset($keys[$key]);
        }

        return empty($keys);
    }


}


