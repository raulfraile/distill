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

    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $this->registerFormats($container);
        $this->registerMethods($container);
        $this->registerStrategies($container);

        $container['distill.format_guesser'] = $container->factory(function ($c) {
            return new FormatGuesser([
                $c['distill.format.bz2'],
                $c['distill.format.cab'],
                $c['distill.format.gz'],
                $c['distill.format.phar'],
                $c['distill.format.rar'],
                $c['distill.format.tar'],
                $c['distill.format.tar_bz2'],
                $c['distill.format.tar_gz'],
                $c['distill.format.tar_xz'],
                $c['distill.format.7z'],
                $c['distill.format.xz'],
                $c['distill.format.zip']
            ]);
        });

        $container['distill.chooser'] = $container->factory(function ($c) {
            return new Chooser($c['distill.strategy.minimum_size'], $c['distill.format_guesser']);
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
        $container['distill.extractor.method.' . Method\ArchiveTarMethod::getName()] = $container->factory(function ($c) {
            return new Method\ArchiveTarMethod();
        });

        $container['distill.extractor.method.' . Method\Bzip2CommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\Bzip2CommandMethod();
        });

        $container['distill.extractor.method.' . Method\CabextractCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\CabextractCommandMethod();
        });

        $container['distill.extractor.method.' . Method\GzipCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\GzipCommandMethod();
        });

        $container['distill.extractor.method.' . Method\PharExtensionMethod::getName()] = $container->factory(function ($c) {
            return new Method\PharExtensionMethod();
        });

        $container['distill.extractor.method.' . Method\PharDataMethod::getName()] = $container->factory(function ($c) {
            return new Method\PharDataMethod();
        });

        $container['distill.extractor.method.' . Method\RarExtensionMethod::getName()] = $container->factory(function ($c) {
            return new Method\RarExtensionMethod();
        });

        $container['distill.extractor.method.' . Method\TarCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\TarCommandMethod();
        });

        $container['distill.extractor.method.' . Method\UnrarCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\UnrarCommandMethod();
        });

        $container['distill.extractor.method.' . Method\UnzipCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\UnzipCommandMethod();
        });

        $container['distill.extractor.method.' . Method\X7zCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\X7zCommandMethod();
        });

        $container['distill.extractor.method.' . Method\XzCommandMethod::getName()] = $container->factory(function ($c) {
            return new Method\XzCommandMethod();
        });

        $container['distill.extractor.method.' . Method\ZipArchiveMethod::getName()] = $container->factory(function ($c) {
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
    }

}
