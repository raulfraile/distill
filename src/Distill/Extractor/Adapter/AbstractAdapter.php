<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Extractor\Adapter;

use Distill\File;
use Symfony\Component\Process\Process;

abstract class AbstractAdapter implements AdapterInterface
{

    protected $methods = array();

    /**
     * {@inheritdoc}
     */
    public function extract(File $file, $path)
    {
        $success = false;
        $methodsCount = count($this->methods);
        $i = 0;

        while (!$success && $i < $methodsCount) {
            $success = call_user_func_array($this->methods[$i], array($file, $path));

            $i++;
        }

        return $success;
    }

    /**
     * Checks whether the command exists in the system.
     * @param string $command Command
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function existsCommand($command)
    {
        if ($this->isWindows()) {
            return false;
        }

        $process = new Process('command ' . $command . ' > /dev/null');
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * Executes a command.
     * @param string $command Command
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function executeCommand($command)
    {
        $process = new Process($command);
        $process->run();
//ld($command, $process->isSuccessful(), $process->getExitCode());
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

}
