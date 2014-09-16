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
use GuzzleHttp\Client;
use Pimple\Container;

class Distill
{

    /**
     * Compressed file extractor.
     * @var ExtractorInterface Extractor
     */
    protected $extractor;

    /**
     * Strategy.
     * @var StrategyInterface
     */
    protected $strategy;

    /**
     * Format guesser.
     * @var FormatGuesserInterface
     */
    protected $formatGuesser;

    /**
     * Files.
     * @var File[]
     */
    protected $files;


    /**
     *
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     * @param ExtractorInterface     $extractor
     * @param StrategyInterface      $strategy
     * @param FormatGuesserInterface $formatGuesser
     */
    public function __construct(
        ExtractorInterface $extractor = null,
        StrategyInterface $strategy = null,
        FormatGuesserInterface $formatGuesser = null
    )
    {



        $this->container = new Container();

        // formats
        $this->container['format.bz2'] = $this->container->factory(function ($c) {
            return new Format\Bz2();
        });
        $this->container['format.cab'] = $this->container->factory(function ($c) {
            return new Format\Cab();
        });
        $this->container['format.gz'] = $this->container->factory(function ($c) {
            return new Format\Gz();
        });
        $this->container['format.phar'] = $this->container->factory(function ($c) {
            return new Format\Phar();
        });
        $this->container['format.rar'] = $this->container->factory(function ($c) {
            return new Format\Rar();
        });
        $this->container['format.tar'] = $this->container->factory(function ($c) {
            return new Format\Tar();
        });
        $this->container['format.tar_bz2'] = $this->container->factory(function ($c) {
            return new Format\TarBz2();
        });
        $this->container['format.tar_gz'] = $this->container->factory(function ($c) {
            return new Format\TarGz();
        });
        $this->container['format.tar_xz'] = $this->container->factory(function ($c) {
            return new Format\TarXz();
        });
        $this->container['format.7z'] = $this->container->factory(function ($c) {
            return new Format\X7z();
        });
        $this->container['format.xz'] = $this->container->factory(function ($c) {
            return new Format\Xz();
        });
        $this->container['format.zip'] = $this->container->factory(function ($c) {
            return new Format\Zip();
        });

        // methods
        $this->container['method.archive_tar'] = $this->container->factory(function ($c) {
            return new Method\ArchiveTarMethod();
        });

        $this->container['method.bzip2_command'] = $this->container->factory(function ($c) {
            return new Method\Bzip2CommandMethod();
        });

        $this->container['method.cabextract_command'] = $this->container->factory(function ($c) {
            return new Method\CabextractCommandMethod();
        });

        $this->container['method.gzip_command'] = $this->container->factory(function ($c) {
            return new Method\GzipCommandMethod();
        });

        $this->container['method.phar_extension'] = $this->container->factory(function ($c) {
            return new Method\PharExtensionMethod();
        });

        $this->container['method.phar_data'] = $this->container->factory(function ($c) {
            return new Method\PharDataMethod();
        });

        $this->container['method.rar_extension'] = $this->container->factory(function ($c) {
            return new Method\RarExtensionMethod();
        });

        $this->container['method.tar_command'] = $this->container->factory(function ($c) {
            return new Method\TarCommandMethod();
        });

        $this->container['method.unrar_command'] = $this->container->factory(function ($c) {
            return new Method\UnrarCommandMethod();
        });

        $this->container['method.unzip_command'] = $this->container->factory(function ($c) {
            return new Method\UnzipCommandMethod();
        });

        $this->container['method.7z_command'] = $this->container->factory(function ($c) {
            return new Method\X7zCommandMethod();
        });

        $this->container['method.xz_command'] = $this->container->factory(function ($c) {
            return new Method\XzCommandMethod();
        });

        $this->container['method.zip_archive'] = $this->container->factory(function ($c) {
            return new Method\ZipArchiveMethod();
        });

        // adapters
        $this->container['adapter.bz2'] = $this->container->factory(function ($c) {
            return new Adapter\Bz2Adapter([
                $c['method.bzip2_command'],
                $c['method.7z_command']
            ]);
        });

        $this->container['adapter.cab'] = $this->container->factory(function ($c) {
            return new Adapter\CabAdapter([
                $c['method.cabextract_command'],
                $c['method.7z_command']
            ]);
        });

        $this->container['adapter.gz'] = $this->container->factory(function ($c) {
            return new Adapter\GzAdapter([
                $c['method.gzip_command'],
                $c['method.7z_command']
            ]);
        });

        $this->container['adapter.rar'] = $this->container->factory(function ($c) {
            return new Adapter\RarAdapter([
                $c['method.unrar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar'],
                $c['method.phar_data'],
            ]);
        });

        $this->container['adapter.tar'] = $this->container->factory(function ($c) {
            return new Adapter\TarAdapter([
                $c['method.tar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar']
            ]);
        });

        $this->container['adapter.tar_bz2'] = $this->container->factory(function ($c) {
            return new Adapter\TarBz2Adapter([
                $c['method.tar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar']
            ]);
        });

        $this->container['adapter.tar_gz'] = $this->container->factory(function ($c) {
            return new Adapter\TarGzAdapter([
                $c['method.tar_command'],
                $c['method.7z_command'],
                $c['method.archive_tar']
            ]);
        });

        $this->container['adapter.tar_xz'] = $this->container->factory(function ($c) {
            return new Adapter\TarXzAdapter([
                $c['method.tar_command']
            ]);
        });

        $this->container['adapter.7z'] = $this->container->factory(function ($c) {
            return new Adapter\X7zAdapter([
                $c['method.7z_command']
            ]);
        });

        $this->container['adapter.zip'] = $this->container->factory(function ($c) {
            return new Adapter\ZipAdapter([
                $c['method.unzip_command'],
                $c['method.7z_command'],
                $c['method.zip_archive']
            ]);
        });

        $this->container['adapter.xz'] = $this->container->factory(function ($c) {
            return new Adapter\XzAdapter([
                $c['method.xz_command'],
                $c['method.7z_command']
            ]);
        });

        $this->container['adapter.phar'] = $this->container->factory(function ($c) {
            return new Adapter\PharAdapter([
                $c['method.phar_extension']
            ]);
        });


        $this->container['format_guesser'] = $this->container->factory(function ($c) {
            return new FormatGuesser();
        });

        $this->container['extractor'] = $this->container->factory(function ($c) {
            return new Extractor([
                $c['adapter.zip']
            ]);
        });


        $this->files = array();
    }

    /**
     * Adds a new file.
     * @param string               $filename File name
     * @param FormatInterface|null $format   Format
     *
     * @return Distill
     */
    public function addFile($filename, FormatInterface $format = null)
    {
        if (null === $format) {
            $format = $this->formatGuesser->guess($filename);
        }

        $this->files[] = new File($filename, $format);

        return $this;
    }

    /**
     * Gets the preferred file based on the chosen strategy.
     *
     * @return File Preferred file
     */
    public function getPreferredFile()
    {
        return $this->strategy->getPreferredFile($this->files);
    }

    public function downloadPreferredFile($destination)
    {
        $client = new Client();

        $response = $client->get($this->getPreferredFile()->getPath());

        return file_put_contents($destination, $response->getBody()) !== false;
    }

    public function downloadPreferredFileAndExtract($destination)
    {
        $preferredFile = $this->getPreferredFile();
        $downloadPath = sys_get_temp_dir() . '/' . basename($preferredFile->getPath());

        $this->downloadPreferredFile($downloadPath);

        $downloadedFile = new File($downloadPath, $preferredFile->getFormat());

        return $this->extract($downloadedFile, $destination);
    }

    /**
     * Extracts the compressed file into the given path.
     * @param File   $file Compressed file
     * @param string $path Destination path
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract(File $file, $path)
    {
        return $this->extractor->extract($file, $path);
    }


    public function extract2($file, $path, $format = null)
    {
        if (null === $format) {
            $format = $this->container['format_guesser']->guess($file);
        }

        return $this->container['extractor']->extract($file, $path, $format);
    }

}
