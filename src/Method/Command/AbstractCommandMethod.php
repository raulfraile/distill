<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Command;

use Distill\Format\FormatInterface;
use Distill\Method\MethodInterface;
use Distill\Method\AbstractMethod;

abstract class AbstractCommandMethod extends AbstractMethod
{
    /**
     * Checks whether the command exists in the system.
     * @param string $command    Command to be checked.
     * @param string $binaryPath Binary path.
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function existsCommand($command, & $binaryPath = null)
    {
        if (!function_exists('exec')) {
            return false;
        }

        if ($this->isWindows()) {
            return false;
        }

        exec('command -v '.$command.' 2>&1', $output, $code);

        if (0 != $code) {
            return false;
        }

        $binaryPath = $output[0];

        return 0 === $code;
    }

    /**
     * Executes a command.
     * @param string $command Command.
     * @param string $output  Command output.
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    protected function executeCommand($command, & $output = null)
    {
        exec($command.' 2>&1', $output, $code);

        return $code;
    }

    /**
     * {@inheritdoc}
     */
    public static function getUncompressionSpeedLevel(FormatInterface $format = null)
    {
        return MethodInterface::SPEED_LEVEL_HIGH;
    }
}
