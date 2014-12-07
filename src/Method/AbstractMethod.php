<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method;

use Distill\Format\FormatInterface;
use Distill\Exception;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractMethod implements MethodInterface
{
    const OS_TYPE_UNIX = 1;
    const OS_TYPE_DARWIN = 2;
    const OS_TYPE_CYGWIN = 3;
    const OS_TYPE_WINDOWS = 4;
    const OS_TYPE_BSD = 5;

    protected $supported = null;

    protected $osType = null;

    protected function getOsType()
    {
        if (null === $this->osType) {
            $this->osType = $this->guessType();
        }

        return $this->osType;
    }

    protected function isExitCodeSuccessful($code)
    {
        return 0 === $code;
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        $className = static::getClass();
        $className = str_replace('\\', '', $className);
        $className = preg_replace('/^DistillMethod/', '', $className);
        $className = strtolower($className);

        return $className;
    }

    /**
     * Gets an object to work with the filesystem.
     *
     * @return Filesystem
     */
    protected function getFilesystem()
    {
        return new Filesystem();
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

    /**
     * Checks whether the code is running on HHVM.
     *
     * @return bool Returns TRUE when running on HHVM, FALSE otherwise
     */
    protected function isHhvm()
    {
        return defined('HHVM_VERSION');
    }

    /**
     * Guesses OS type.
     *
     * @return int
     */
    protected function guessType()
    {
        $os = strtolower(PHP_OS);
        if (false !== strpos($os, 'cygwin')) {
            return self::OS_TYPE_CYGWIN;
        }
        if (false !== strpos($os, 'darwin')) {
            return self::OS_TYPE_DARWIN;
        }
        if (false !== strpos($os, 'bsd')) {
            return self::OS_TYPE_BSD;
        }
        if (0 === strpos($os, 'win')) {
            return self::OS_TYPE_WINDOWS;
        }

        return self::OS_TYPE_UNIX;
    }

    /**
     * {@inheritdoc}
     */
    public static function getUncompressionSpeedLevel(FormatInterface $format = null)
    {
        return MethodInterface::SPEED_LEVEL_MIDDLE;
    }

    protected function checkSupport(FormatInterface $format)
    {
        if (!$this->isSupported()) {
            throw new Exception\Method\MethodNotSupportedException($this);
        }

        if (false === $this->isFormatSupported($format)) {
            throw new Exception\Method\FormatNotSupportedInMethodException($this, $format);
        }
    }
}
