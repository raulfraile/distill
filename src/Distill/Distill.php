<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill;

use Distill\Extractor\ExtractorInterface;
use Distill\Strategy\StrategyInterface;
use Distill\Format\FormatInterface;
use Pimple\Container;

class Distill
{

    /**
     * Compressed file extractor.
     * @var ExtractorInterface Extractor
     */
    protected $extractor;

    /**
     * Strategy.
     * @var StrategyInterface
     */
    protected $strategy;

    /**
     * Format guesser.
     * @var FormatGuesserInterface
     */
    protected $formatGuesser;

    /**
     * Files.
     * @var File[]
     */
    protected $files;

    /**
     * Container.
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->container->register(new ContainerProvider());
    }

    /**
     * Extracts the compressed file into the given path.
     * @param string                 $file   Compressed file
     * @param string                 $path   Destination path
     * @param Format\FormatInterface $format
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract($file, $path, FormatInterface $format = null)
    {
        if (null === $format) {
            $format = $this->container['distill.format_guesser']->guess($file);
        }

        return $this->container['distill.extractor.extractor']->extract($file, $path, $format);
    }

    /**
     * Extracts the compressed file and copies the files from the root directory.
     * @param string                 $file   Compressed file
     * @param string                 $path   Destination path
     * @param Format\FormatInterface $format
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extractWithoutRootDirectory($file, $path, FormatInterface $format = null)
    {
        // extract to a temporary place
        $tempDirectory = sys_get_temp_dir() . uniqid(time()) . DIRECTORY_SEPARATOR;
        $this->extract($file, $tempDirectory, $format);

        // move directory
        $iterator = new \FilesystemIterator($tempDirectory, \FilesystemIterator::SKIP_DOTS);

        $hasSingleRootDirectory = false;
        $singleRootDirectoryName = null;

        while ($iterator->valid()) {
            $uncompressedResource = $iterator->current();

            $iterator->next();

            if (false === $hasSingleRootDirectory && true === $uncompressedResource->isDir()) {
                $hasSingleRootDirectory = true;
                $singleRootDirectoryName = $uncompressedResource->getRealPath();

                continue;
            }

            $hasSingleRootDirectory = false;
        }

        if (true === $hasSingleRootDirectory) {
            $this->rrmdir($path);
            return rename($singleRootDirectoryName, $path);
        }

        // it is not a compressed file with a single directory

        return false;
    }

    /**
     * Recursively removes a directory.
     * @param string $path Directory path.
     */
    protected function rrmdir($path) {

        if (!is_dir($path)) {
            return true;
        }

        $iterator = new \DirectoryIterator($path);
        foreach($iterator as $file) {
            if($file->isFile()) {
                unlink($file->getRealPath());
            } else if(!$file->isDot() && $file->isDir()) {
                $this->rrmdir($file->getRealPath());
            }
        }

        return rmdir($path);
    }

    /**
     * Gets the file chooser.
     *
     * @return Chooser
     */
    public function getChooser()
    {
        return $this->container['distill.chooser'];
    }

}
