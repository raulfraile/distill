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

use Distill\Format\FormatInterface;
use Distill\Extractor\Method\MethodInterface;
use Symfony\Component\Process\Process;

abstract class AbstractAdapter implements AdapterInterface
{

    /**
     * @var MethodInterface[]
     */
    protected $methods = array();

    /**
     * Constructor.
     *
     * @param MethodInterface[] $methods
     */
    public function __construct($methods = [])
    {
        $this->methods = $methods;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($file, $path, FormatInterface $format)
    {
        $success = false;
        $methodsCount = count($this->methods);
        $i = 0;

        while (!$success && $i < $methodsCount) {
            $success = $this->methods[$i]->extract($file, $path, $format);

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

    protected function hasSupportedMethods()
    {
        $i = 0;
        $supported = false;
        $methodsCount = count($this->methods);
        while (!$supported && $i < $methodsCount) {
            $supported = $this->methods[$i]->isSupported();
        }

        return $supported;
    }

}
