<?php

namespace Distill\Tests;

use Symfony\Component\Finder\Finder;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Test files path
     * @var string
     */
    protected $filesPath;

    public function setUp()
    {
        $this->filesPath = __DIR__ . '/../../files/';
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
        $finder = new Finder();
        $finder->in($directory)
            ->depth('< 3')
            ->sortByName();

        $hash = hash_init('sha512');
        foreach ($finder as $file) {
            hash_update($hash, $file->getRelativePathname() . $file->getContents());
        }

        return hash_final($hash);
    }
}


