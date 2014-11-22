<?php

namespace Distill\Tests\Method;

use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Distill\Tests\TestCase;
use Symfony\Component\Process\Process;

abstract class AbstractMethodTest extends TestCase
{

    /** @var MethodInterface $method */
    protected $method;

    protected function extract($file, $target, FormatInterface $format)
    {
        return $this->method->extract($this->filesPath . $file, $target, $format);
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

        $process = new Process('command -v ' . $command . ' > /dev/null');
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

}
