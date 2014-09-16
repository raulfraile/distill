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
use Distill\Extractor\Extractor;
use Distill\Extractor\ExtractorInterface;
use Distill\Format;
use Distill\Extractor\Method;
use Distill\Strategy\MinimumSize;
use Distill\Strategy\StrategyInterface;
use Distill\Format\FormatInterface;
use Distill\Strategy\UncompressionSpeed;
use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ContainerProvider implements ServiceProviderInterface
{

    public function register(Container $container)
    {
        
        // formats
        $container['format.bz2'] = $container->factory(function ($c) {
            return new Format\Bz2();
        });
        $container['format.cab'] = $container->factory(function ($c) {
            return new Format\Cab();
        });
        $container['format.gz'] = $container->factory(function ($c) {
            return new Format\Gz();
        });
        $container['format.phar'] = $container->factory(function ($c) {
            return new Format\Phar();
        });
        $container['format.rar'] = $container->factory(function ($c) {
            return new Format\Rar();
        });
        $container['format.tar'] = $container->factory(function ($c) {
            return new Format\Tar();
        });
        $container['format.tar_bz2'] = $container->factory(function ($c) {
            return new Format\TarBz2();
        });
        $container['format.tar_gz'] = $container->factory(function ($c) {
            return new Format\TarGz();
        });
        $container['format.tar_xz'] = $container->factory(function ($c) {
            return new Format\TarXz();
        });
        $container['format.7z'] = $container->factory(function ($c) {
            return new Format\X7z();
        });
        $container['format.xz'] = $container->factory(function ($c) {
            return new Format\Xz();
        });
        $container['format.zip'] = $container->factory(function ($c) {
            return new Format\Zip();
        });

        // methods
        $container['method.archive_tar'] = $container->factory(function ($c) {
            return new Method\ArchiveTarMethod();
        });

        $container['method.bzip2_command'] = $container->factory(function ($c) {
            return new Method\Bzip2CommandMethod();
        });

        $container['method.cabextract_command'] = $container->factory(function ($c) {
            return new Method\CabextractCommandMethod();
        });

        $container['method.gzip_command'] = $container->factory(function ($c) {
            return new Method\GzipCommandMethod();
        });

        $container['method.phar_extension'] = $container->factory(function ($c) {
            return new Method\PharExtensionMethod();
        });

        $container['method.phar_data'] = $container->factory(function ($c) {
            return new Method\PharDataMethod();
        });

        $container['method.rar_extension'] = $container->factory(function ($c) {
            return new Method\RarExtensionMethod();
        });

        $container['method.tar_command'] = $container->factory(function ($c) {
            return new Method\TarCommandMethod();
        });

        $container['method.unrar_command'] = $container->factory(function ($c) {
            return new Method\UnrarCommandMethod();
        });

        $container['method.unzip_command'] = $container->factory(function ($c) {
            return new Method\UnzipCommandMethod();
        });

        $container['method.7z_command'] = $container->factory(function ($c) {
            return new Method\X7zCommandMethod();
        });

        $container['method.xz_command'] = $container->factory(function ($c) {
            return new Method\XzCommandMethod();
        });

        $container['method.zip_archive'] = $container->factory(function ($c) {
            return new Method\ZipArchiveMethod();
        });

        // adapters
        $container['adapter.bz2'] = $container->factory(function ($c) {
            return new Adapter\Bz2Adapter([
                $c['method.bzip2_command'],
                $c['method.7z_command']
            ]);
        });

        $container['adapter.cab'] = $container->factory(function ($c) {
            return new Adapter\CabAdapter([
                $c['method.cabextract_command'],
                $c['method.7z_command']
            ]);
        });

        $container['adapter.gz'] = $container->factory(function ($c) {
            return new Adapter\GzAdapter([
                $c['method.gzip_command'],
                $c['method.7z_command']
            ]);
        });

        $container['adapter.rar'] = $container->factory(function ($c) {
            return new Adapter\RarAdapter([
                $c['method.unrar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar'],
                $c['method.phar_data'],
            ]);
        });

        $container['adapter.tar'] = $container->factory(function ($c) {
            return new Adapter\TarAdapter([
                $c['method.tar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar']
            ]);
        });

        $container['adapter.tar_bz2'] = $container->factory(function ($c) {
            return new Adapter\TarBz2Adapter([
                $c['method.tar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar']
            ]);
        });

        $container['adapter.tar_gz'] = $container->factory(function ($c) {
            return new Adapter\TarGzAdapter([
                $c['method.tar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar']
            ]);
        });

        $container['adapter.tar_xz'] = $container->factory(function ($c) {
            return new Adapter\TarXzAdapter([
                $c['method.tar_command']
            ]);
        });

        $container['adapter.7z'] = $container->factory(function ($c) {
            return new Adapter\X7zAdapter([
                $c['method.7z_command']
            ]);
        });

        $container['adapter.zip'] = $container->factory(function ($c) {
            return new Adapter\ZipAdapter([
                $c['method.unzip_command'],
                $c['method.7z_command'],
                $c['method.zip_archive']
            ]);
        });

        $container['adapter.xz'] = $container->factory(function ($c) {
            return new Adapter\XzAdapter([
                $c['method.xz_command'],
                $c['method.7z_command']
            ]);
        });

        $container['adapter.phar'] = $container->factory(function ($c) {
            return new Adapter\PharAdapter([
                $c['method.phar_extension']
            ]);
        });


        $container['format_guesser'] = $container->factory(function ($c) {
            return new FormatGuesser();
        });

        $container['extractor'] = $container->factory(function ($c) {
            return new Extractor([
                $c['adapter.zip']
            ]);
        });

        // strategies

        $container['distill.strategy.minimum_size'] = $container->factory(function ($c) {
            return new MinimumSize();
        });
        $container['distill.strategy.uncompression_speed'] = $container->factory(function ($c) {
            return new UncompressionSpeed();
        });



        $container['distill.chooser'] = $container->factory(function ($c) {
            return new Chooser($c['distill.strategy.minimum_size'], $c['format_guesser']);
        });
    }



}
