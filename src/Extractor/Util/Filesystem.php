<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Util;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Taken from Composer: https://github.com/composer/composer/blob/master/src/Composer/Util/Filesystem.php
 */
class Filesystem
{
    private $processExecutor;

    public function __construct(ProcessExecutor $executor = null)
    {
        $this->processExecutor = $executor ?: new ProcessExecutor();
    }

    public function remove($file)
    {
        if (is_dir($file)) {
            return $this->removeDirectory($file);
        }

        if (file_exists($file)) {
            return $this->unlink($file);
        }

        return false;
    }

    /**
     * Recursively remove a directory
     *
     * Uses the process component if proc_open is enabled on the PHP
     * installation.
     *
     * @param  string $directory
     * @return bool
     *
     * @throws \RuntimeException
     */
    private function removeDirectory($directory)
    {
        if ($this->isSymlinkedDirectory($directory)) {
            return $this->unlinkSymlinkedDirectory($directory);
        }

        if (!file_exists($directory) || !is_dir($directory)) {
            return true;
        }

        if (preg_match('{^(?:[a-z]:)?[/\\\\]+$}i', $directory)) {
            throw new \RuntimeException('Aborting an attempted deletion of '.$directory.', this was probably not intended, if it is a real use case please report it.');
        }

        if (!function_exists('proc_open')) {
            return $this->removeDirectoryPhp($directory);
        }

        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            $cmd = sprintf('rmdir /S /Q %s', ProcessExecutor::escape(realpath($directory)));
        } else {
            $cmd = sprintf('rm -rf %s', ProcessExecutor::escape($directory));
        }

        $result = $this->getProcess()->execute($cmd, $output) === 0;

        // clear stat cache because external processes aren't tracked by the php stat cache
        clearstatcache();

        if ($result && !file_exists($directory)) {
            return true;
        }

        return $this->removeDirectoryPhp($directory);
    }

    /**
     * Recursively delete directory using PHP iterators.
     *
     * Uses a CHILD_FIRST RecursiveIteratorIterator to sort files
     * before directories, creating a single non-recursive loop
     * to delete files/directories in the correct order.
     *
     * @param  string $directory
     * @return bool
     */
    private function removeDirectoryPhp($directory)
    {
        $it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($ri as $file) {
            if ($file->isDir()) {
                $this->rmdir($file->getPathname());
            } else {
                $this->unlink($file->getPathname());
            }
        }

        return $this->rmdir($directory);
    }

    private function ensureDirectoryExists($directory)
    {
        if (!is_dir($directory)) {
            if (file_exists($directory)) {
                throw new \RuntimeException(
                    $directory.' exists and is not a directory.'
                );
            }
            if (!@mkdir($directory, 0777, true)) {
                throw new \RuntimeException(
                    $directory.' does not exist and could not be created.'
                );
            }
        }
    }

    /**
     * Attempts to unlink a file and in case of failure retries after 350ms on windows
     *
     * @param  string $path
     * @return bool
     *
     * @throws \RuntimeException
     */
    private function unlink($path)
    {
        if (!@$this->unlinkImplementation($path)) {
            // retry after a bit on windows since it tends to be touchy with mass removals
            if (!defined('PHP_WINDOWS_VERSION_BUILD') || (usleep(350000) && !@$this->unlinkImplementation($path))) {
                $error = error_get_last();
                $message = 'Could not delete '.$path.': '.@$error['message'];
                if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                    $message .= "\nThis can be due to an antivirus or the Windows Search Indexer locking the file while they are analyzed";
                }

                throw new \RuntimeException($message);
            }
        }

        return true;
    }

    /**
     * Attempts to rmdir a file and in case of failure retries after 350ms on windows
     *
     * @param  string $path
     * @return bool
     *
     * @throws \RuntimeException
     */
    private function rmdir($path)
    {
        if (!@rmdir($path)) {
            // retry after a bit on windows since it tends to be touchy with mass removals
            if (!defined('PHP_WINDOWS_VERSION_BUILD') || (usleep(350000) && !@rmdir($path))) {
                $error = error_get_last();
                $message = 'Could not delete '.$path.': '.@$error['message'];
                if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                    $message .= "\nThis can be due to an antivirus or the Windows Search Indexer locking the file while they are analyzed";
                }

                throw new \RuntimeException($message);
            }
        }

        return true;
    }

    /**
     * Copy then delete is a non-atomic version of {@link rename}.
     *
     * Some systems can't rename and also don't have proc_open,
     * which requires this solution.
     *
     * @param string $source
     * @param string $target
     */
    private function copyThenRemove($source, $target)
    {
        if (!is_dir($source)) {
            copy($source, $target);
            $this->unlink($source);

            return;
        }

        $it = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
        $this->ensureDirectoryExists($target);

        foreach ($ri as $file) {
            $targetPath = $target.DIRECTORY_SEPARATOR.$ri->getSubPathName();
            if ($file->isDir()) {
                $this->ensureDirectoryExists($targetPath);
            } else {
                copy($file->getPathname(), $targetPath);
            }
        }

        $this->removeDirectoryPhp($source);
    }

    public function rename($source, $target)
    {
        if (true === @rename($source, $target)) {
            return;
        }

        if (!function_exists('proc_open')) {
            return $this->copyThenRemove($source, $target);
        }

        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            // Try to copy & delete - this is a workaround for random "Access denied" errors.
            $command = sprintf('xcopy %s %s /E /I /Q', ProcessExecutor::escape($source), ProcessExecutor::escape($target));
            $result = $this->processExecutor->execute($command, $output);

            // clear stat cache because external processes aren't tracked by the php stat cache
            clearstatcache();

            if (0 === $result) {
                $this->remove($source);

                return;
            }
        } else {
            // We do not use PHP's "rename" function here since it does not support
            // the case where $source, and $target are located on different partitions.
            $command = sprintf('mv %s %s', ProcessExecutor::escape($source), ProcessExecutor::escape($target));
            $result = $this->processExecutor->execute($command, $output);

            // clear stat cache because external processes aren't tracked by the php stat cache
            clearstatcache();

            if (0 === $result) {
                return;
            }
        }

        return $this->copyThenRemove($source, $target);
    }

    private function getProcess()
    {
        return new ProcessExecutor();
    }

    /**
     * delete symbolic link implementation (commonly known as "unlink()")
     *
     * symbolic links on windows which link to directories need rmdir instead of unlink
     *
     * @param string $path
     *
     * @return bool
     */
    private function unlinkImplementation($path)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD') && is_dir($path) && is_link($path)) {
            return rmdir($path);
        }

        return unlink($path);
    }

    private function isSymlinkedDirectory($directory)
    {
        if (!is_dir($directory)) {
            return false;
        }

        $resolved = $this->resolveSymlinkedDirectorySymlink($directory);

        return is_link($resolved);
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    private function unlinkSymlinkedDirectory($directory)
    {
        $resolved = $this->resolveSymlinkedDirectorySymlink($directory);

        return $this->unlink($resolved);
    }

    /**
     * resolve pathname to symbolic link of a directory
     *
     * @param string $pathname directory path to resolve
     *
     * @return string resolved path to symbolic link or original pathname (unresolved)
     */
    private function resolveSymlinkedDirectorySymlink($pathname)
    {
        if (!is_dir($pathname)) {
            return $pathname;
        }

        $resolved = rtrim($pathname, '/');

        if (!strlen($resolved)) {
            return $pathname;
        }

        return $resolved;
    }
}
