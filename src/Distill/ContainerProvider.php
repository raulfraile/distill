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
use Distill\Extractor\Extractor;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ContainerProvider implements ServiceProviderInterface
{

    protected $formats = [
        'bz2', 'cab', 'gz', 'phar', 'rar', 'tar', 'tar_bz2', 'tar_gz', 'tar_xz', '7z', 'xz', 'zip'
    ];

    protected $methods;

    public function __construct()
    {
        $this->formats = [
            'bz2', 'cab', 'gz', 'phar', 'rar', 'tar', 'tar_bz2', 'tar_gz', 'tar_xz', '7z', 'xz', 'zip'
        ];

        $this->methods = [
            Method\ArchiveTarMethod::getName(),
            Method\Bzip2CommandMethod::getName(),
            Method\CabextractCommandMethod::getName(),
            Method\GzipCommandMethod::getName(),
            Method\PharExtensionMethod::getName(),
            Method\PharDataMethod::getName(),
            Method\RarExtensionMethod::getName(),
            Method\TarCommandMethod::getName(),
            Method\UnrarCommandMethod::getName(),
            Method\UnzipCommandMethod::getName(),
            Method\X7zCommandMethod::getName(),
            Method\XzCommandMethod::getName(),
            Method\ZipArchiveMethod::getName()
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
            $formats = $this->formats;
            $callback = function($format) use ($c, $formats) {
                return $c['distill.format.' . $format];
            };

            return new FormatGuesser(array_map($callback, $this->formats));
        });

        $methods = $this->methods;
        $callbackMethods = function($method) use ($container, $methods) {
            return $container['distill.method.' . $method];
        };

        $container['distill.support_checker'] = $container->factory(function ($c) use ($callbackMethods) {

            return new SupportChecker(array_map($callbackMethods, $this->methods));
        });

        $container['distill.chooser'] = $container->factory(function ($c) {
            return new Chooser(
                $c['distill.support_checker'],
                $c['distill.strategy.minimum_size'],
                $c['distill.format_guesser']
            );
        });

        $container['distill.extractor.extractor'] = $container->factory(function ($c) {
            return new Extractor($c);
        });
    }

    /**
     * Registers the formats.
     * @param Container $container Container
     */
    protected function registerFormats(Container $container)
    {
        $container['distill.format.bz2'] = $container->factory(function ($c) {
            return new Format\Bz2();
        });
        $container['distill.format.cab'] = $container->factory(function ($c) {
            return new Format\Cab();
        });
        $container['distill.format.gz'] = $container->factory(function ($c) {
            return new Format\Gz();
        });
        $container['distill.format.phar'] = $container->factory(function ($c) {
            return new Format\Phar();
        });
        $container['distill.format.rar'] = $container->factory(function ($c) {
            return new Format\Rar();
        });
        $container['distill.format.tar'] = $container->factory(function ($c) {
            return new Format\Tar();
        });
        $container['distill.format.tar_bz2'] = $container->factory(function ($c) {
            return new Format\TarBz2();
        });
        $container['distill.format.tar_gz'] = $container->factory(function ($c) {
            return new Format\TarGz();
        });
        $container['distill.format.tar_xz'] = $container->factory(function ($c) {
            return new Format\TarXz();
        });
        $container['distill.format.7z'] = $container->factory(function ($c) {
            return new Format\X7z();
        });
        $container['distill.format.xz'] = $container->factory(function ($c) {
            return new Format\Xz();
        });
        $container['distill.format.zip'] = $container->factory(function ($c) {
            return new Format\Zip();
        });
    }

    /**
     * Register the uncompression methods.
     * @param Container $container
     */
    protected function registerMethods(Container $container)
    {
        $container['distill.method.' . Method\ArchiveTarMethod::getName()] = $container->factory(function ($c) {
            return new Method\ArchiveTarMethod();
        });

        $container['distill.method.' . Method\Bzip2CommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\Bzip2CommandMethod();
        });

        $container['distill.method.' . Method\CabextractCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\CabextractCommandMethod();
        });

        $container['distill.method.' . Method\GzipCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\GzipCommandMethod();
        });

        $container['distill.method.' . Method\PharExtensionMethod::getName()] = $container->factory(function ($c) {
            return new Method\PharExtensionMethod();
        });

        $container['distill.method.' . Method\PharDataMethod::getName()] = $container->factory(function ($c) {
            return new Method\PharDataMethod();
        });

        $container['distill.method.' . Method\RarExtensionMethod::getName()] = $container->factory(function ($c) {
            return new Method\RarExtensionMethod();
        });

        $container['distill.method.' . Method\TarCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\TarCommandMethod();
        });

        $container['distill.method.' . Method\UnrarCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\UnrarCommandMethod();
        });

        $container['distill.method.' . Method\UnzipCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\UnzipCommandMethod();
        });

        $container['distill.method.' . Method\X7zCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\X7zCommandMethod();
        });

        $container['distill.method.' . Method\XzCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\XzCommandMethod();
        });

        $container['distill.method.' . Method\ZipArchiveMethod::getName()] = $container->factory(function ($c) {
            return new Method\ZipArchiveMethod();
        });
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

}
