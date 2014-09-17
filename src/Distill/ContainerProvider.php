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

use Distill\Extractor\Adapter;
use Distill\Extractor\Method;
use Distill\Extractor\Extractor;
use Distill\Format;
use Distill\Strategy;
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
        $this->registerAdapters($container);
        $this->registerStrategies($container);

        $container['distill.format_guesser'] = $container->factory(function ($c) {
            return new FormatGuesser([
                $c['distill.extractor.format.bz2'],
                $c['distill.extractor.format.cab'],
                $c['distill.extractor.format.gz'],
                $c['distill.extractor.format.phar'],
                $c['distill.extractor.format.rar'],
                $c['distill.extractor.format.tar'],
                $c['distill.extractor.format.tar_bz2'],
                $c['distill.extractor.format.tar_gz'],
                $c['distill.extractor.format.tar_xz'],
                $c['distill.extractor.format.7z'],
                $c['distill.extractor.format.xz'],
                $c['distill.extractor.format.zip']
            ]);
        });

        $container['distill.chooser'] = $container->factory(function ($c) {
            return new Chooser($c['distill.strategy.minimum_size'], $c['distill.format_guesser']);
        });

        $container['distill.extractor.extractor'] = $container->factory(function ($c) {
            return new Extractor([
                $c['distill.extractor.adapter.bz2'],
                $c['distill.extractor.adapter.cab'],
                $c['distill.extractor.adapter.cab'],
                $c['distill.extractor.adapter.gz'],
                $c['distill.extractor.adapter.phar'],
                $c['distill.extractor.adapter.rar'],
                $c['distill.extractor.adapter.tar'],
                $c['distill.extractor.adapter.tar_bz2'],
                $c['distill.extractor.adapter.tar_gz'],
                $c['distill.extractor.adapter.tar_xz'],
                $c['distill.extractor.adapter.xz'],
                $c['distill.extractor.adapter.zip']
            ]);
        });
    }

    protected function registerFormats(Container $container)
    {
        $container['distill.extractor.format.bz2'] = $container->factory(function ($c) {
            return new Format\Bz2();
        });
        $container['distill.extractor.format.cab'] = $container->factory(function ($c) {
            return new Format\Cab();
        });
        $container['distill.extractor.format.gz'] = $container->factory(function ($c) {
            return new Format\Gz();
        });
        $container['distill.extractor.format.phar'] = $container->factory(function ($c) {
            return new Format\Phar();
        });
        $container['distill.extractor.format.rar'] = $container->factory(function ($c) {
            return new Format\Rar();
        });
        $container['distill.extractor.format.tar'] = $container->factory(function ($c) {
            return new Format\Tar();
        });
        $container['distill.extractor.format.tar_bz2'] = $container->factory(function ($c) {
            return new Format\TarBz2();
        });
        $container['distill.extractor.format.tar_gz'] = $container->factory(function ($c) {
            return new Format\TarGz();
        });
        $container['distill.extractor.format.tar_xz'] = $container->factory(function ($c) {
            return new Format\TarXz();
        });
        $container['distill.extractor.format.7z'] = $container->factory(function ($c) {
            return new Format\X7z();
        });
        $container['distill.extractor.format.xz'] = $container->factory(function ($c) {
            return new Format\Xz();
        });
        $container['distill.extractor.format.zip'] = $container->factory(function ($c) {
            return new Format\Zip();
        });
    }

    /**
     * Register methods.
     * @param Container $container
     */
    protected function registerMethods(Container $container)
    {
        $container['distill.extractor.method.archive_tar'] = $container->factory(function ($c) {
            return new Method\ArchiveTarMethod();
        });

        $container['distill.extractor.method.bzip2_command'] = $container->factory(function ($c) {
            return new Method\Bzip2CommandMethod();
        });

        $container['distill.extractor.method.cabextract_command'] = $container->factory(function ($c) {
            return new Method\CabextractCommandMethod();
        });

        $container['distill.extractor.method.gzip_command'] = $container->factory(function ($c) {
            return new Method\GzipCommandMethod();
        });

        $container['distill.extractor.method.phar_extension'] = $container->factory(function ($c) {
            return new Method\PharExtensionMethod();
        });

        $container['distill.extractor.method.phar_data'] = $container->factory(function ($c) {
            return new Method\PharDataMethod();
        });

        $container['distill.extractor.method.rar_extension'] = $container->factory(function ($c) {
            return new Method\RarExtensionMethod();
        });

        $container['distill.extractor.method.tar_command'] = $container->factory(function ($c) {
            return new Method\TarCommandMethod();
        });

        $container['distill.extractor.method.unrar_command'] = $container->factory(function ($c) {
            return new Method\UnrarCommandMethod();
        });

        $container['distill.extractor.method.unzip_command'] = $container->factory(function ($c) {
            return new Method\UnzipCommandMethod();
        });

        $container['distill.extractor.method.7z_command'] = $container->factory(function ($c) {
            return new Method\X7zCommandMethod();
        });

        $container['distill.extractor.method.xz_command'] = $container->factory(function ($c) {
            return new Method\XzCommandMethod();
        });

        $container['distill.extractor.method.zip_archive'] = $container->factory(function ($c) {
            return new Method\ZipArchiveMethod();
        });
    }

    protected function registerAdapters(Container $container)
    {
        $container['distill.extractor.adapter.bz2'] = $container->factory(function ($c) {
            return new Adapter\Bz2Adapter([
                $c['distill.extractor.method.bzip2_command'],
                $c['distill.extractor.method.7z_command']
            ]);
        });

        $container['distill.extractor.adapter.cab'] = $container->factory(function ($c) {
            return new Adapter\CabAdapter([
                $c['distill.extractor.method.cabextract_command'],
                $c['distill.extractor.method.7z_command']
            ]);
        });

        $container['distill.extractor.adapter.gz'] = $container->factory(function ($c) {
            return new Adapter\GzAdapter([
                $c['distill.extractor.method.gzip_command'],
                $c['distill.extractor.method.7z_command']
            ]);
        });

        $container['distill.extractor.adapter.rar'] = $container->factory(function ($c) {
            return new Adapter\RarAdapter([
                $c['distill.extractor.method.unrar_command'],
                $c['distill.extractor.method.7z_command'],
                $c['distill.extractor.method.archive_tar'],
                $c['distill.extractor.method.phar_data'],
            ]);
        });

        $container['distill.extractor.adapter.tar'] = $container->factory(function ($c) {
            return new Adapter\TarAdapter([
                $c['distill.extractor.method.tar_command'],
                $c['distill.extractor.method.7z_command'],
                $c['distill.extractor.method.archive_tar']
            ]);
        });

        $container['distill.extractor.adapter.tar_bz2'] = $container->factory(function ($c) {
            return new Adapter\TarBz2Adapter([
                $c['distill.extractor.method.tar_command'],
                $c['distill.extractor.method.7z_command'],
                $c['distill.extractor.method.archive_tar']
            ]);
        });

        $container['distill.extractor.adapter.tar_gz'] = $container->factory(function ($c) {
            return new Adapter\TarGzAdapter([
                $c['distill.extractor.method.tar_command'],
                $c['distill.extractor.method.7z_command'],
                $c['distill.extractor.method.archive_tar']
            ]);
        });

        $container['distill.extractor.adapter.tar_xz'] = $container->factory(function ($c) {
            return new Adapter\TarXzAdapter([
                $c['distill.extractor.method.tar_command']
            ]);
        });

        $container['distill.extractor.adapter.7z'] = $container->factory(function ($c) {
            return new Adapter\X7zAdapter([
                $c['distill.extractor.method.7z_command']
            ]);
        });

        $container['distill.extractor.adapter.zip'] = $container->factory(function ($c) {
            return new Adapter\ZipAdapter([
                $c['distill.extractor.method.unzip_command'],
                $c['distill.extractor.method.7z_command'],
                $c['distill.extractor.method.zip_archive']
            ]);
        });

        $container['distill.extractor.adapter.xz'] = $container->factory(function ($c) {
            return new Adapter\XzAdapter([
                $c['distill.extractor.method.xz_command'],
                $c['distill.extractor.method.7z_command']
            ]);
        });

        $container['distill.extractor.adapter.phar'] = $container->factory(function ($c) {
            return new Adapter\PharAdapter([
                $c['distill.extractor.method.phar_extension']
            ]);
        });
    }

    protected function registerStrategies(Container $container)
    {
        $container['distill.strategy.minimum_size'] = $container->factory(function ($c) {
            return new Strategy\MinimumSize();
        });
        $container['distill.strategy.uncompression_speed'] = $container->factory(function ($c) {
            return new Strategy\UncompressionSpeed();
        });
    }


}
