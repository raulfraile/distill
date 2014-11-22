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

use Distill\Method;
use Distill\Format;
use Distill\Extractor\Extractor;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ContainerProvider implements ServiceProviderInterface
{

    /**
     * Available formats.
     * @var string[]
     */
    protected $formats;

    /**
     * Available methods.
     * @var string[]
     */
    protected $methods;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->formats = [
            Format\Bz2::getClass(),
            Format\Cab::getClass(),
            Format\Gz::getClass(),
            Format\Phar::getClass(),
            Format\Rar::getClass(),
            Format\Tar::getClass(),
            Format\TarBz2::getClass(),
            Format\TarGz::getClass(),
            Format\TarXz::getClass(),
            Format\x7z::getClass(),
            Format\Xz::getClass(),
            Format\Zip::getClass()
        ];

        $this->methods = [
            Method\Extension\Pear\ArchiveTar::getClass(),
            Method\Command\Bzip2::getClass(),
            Method\Command\Cabextract::getClass(),
            Method\Command\GnuGzip::getClass(),
            Method\Extension\Phar::getClass(),
            Method\Extension\PharData::getClass(),
            Method\Extension\Rar::getClass(),
            Method\Command\GnuTar::getClass(),
            Method\Command\Unrar::getClass(),
            Method\Command\Unzip::getClass(),
            Method\Command\x7zip::getClass(),
            Method\Command\Xz::getClass(),
            Method\Extension\Zip::getClass()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $this->registerFormats($container);
        $this->registerMethods($container);
        $this->registerStrategies($container);

        $container['distill.format_guesser'] = $container->factory(function ($c) {
            return new FormatGuesser($this->getFormatsFromContainer($c));
        });

        $container['distill.support_checker'] = $container->factory(function ($c) {
            return new SupportChecker($this->getMethodsFromContainer($c));
        });

        $container['distill.chooser'] = $container->factory(function ($c) {
            return new Chooser(
                $c['distill.support_checker'],
                $c['distill.strategy.minimum_size'],
                $c['distill.format_guesser']
            );
        });

        $container['distill.extractor.extractor'] = $container->factory(function ($c) {
            return new Extractor($this->getMethodsFromContainer($c));
        });
    }

    /**
     * Registers the formats.
     * @param Container $container Container
     */
    protected function registerFormats(Container $container)
    {
        foreach ($this->formats as $formatClass) {
            $container['distill.format.' . $formatClass::getName()] = $container->factory(function ($c) use ($formatClass) {
                return new $formatClass();
            });
        }
    }

    /**
     * Register the uncompression methods.
     * @param Container $container
     */
    protected function registerMethods(Container $container)
    {
        foreach ($this->methods as $methodClass) {
            $container['distill.method.' . $methodClass::getName()] = $container->factory(function ($c) use ($methodClass) {
                return new $methodClass();
            });
        }
    }

    protected function registerStrategies(Container $container)
    {
        $container['distill.strategy.' . Strategy\MinimumSize::getName()] = $container->factory(function ($c) {
            return new Strategy\MinimumSize();
        });
        $container['distill.strategy.' . Strategy\UncompressionSpeed::getName()] = $container->factory(function ($c) {
            return new Strategy\UncompressionSpeed();
        });
        $container['distill.strategy.' . Strategy\Random::getName()] = $container->factory(function ($c) {
            return new Strategy\Random();
        });
    }

    protected function getFormatsFromContainer(Container $container)
    {
        $formats = $this->formats;
        $callback = function($format) use ($container, $formats) {
            return $container['distill.format.' . $format::getName()];
        };

        return array_map($callback, $this->formats);
    }

    protected function getMethodsFromContainer(Container $container)
    {
        $methods = $this->methods;
        $callback = function($method) use ($container, $methods) {
            return $container['distill.method.' . $method::getName()];
        };

        return array_map($callback, $this->methods);
    }

}
