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


    public function __construct()
    {
        $this->container = new Container();
        $this->container->register(new ContainerProvider());
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
    public function getPreferredFile(array $files = [])
    {
        return $this->strategy->getPreferredFile($files);
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
     * @param string $file         Compressed file
     * @param string $path         Destination path
     * @param FormatInterface|null Format (if null, it is guessed by the extension)
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract($file, $path, FormatInterface $format = null)
    {
        if (null === $format) {
            $format = $this->container['format_guesser']->guess($file);
        }

        return $this->container['extractor']->extract($file, $path, $format);
    }


    /**
     *
     * @return Chooser
     */
    public function getChooser()
    {
        return $this->container['distill.chooser'];
    }

    public function getExtractor()
    {
        return $this->container['distill.extractor'];
    }

}
