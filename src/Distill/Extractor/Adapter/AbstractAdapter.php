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

    protected function existsCommand($command)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return false;
        }

        $process = new Process('command ' . $command . ' > /dev/null');
        $process->run();

        return $process->isSuccessful();
    }

    protected function executeCommand($command)
    {
        $process = new Process($command);
        $process->run();

        return $process->isSuccessful();
    }



}
