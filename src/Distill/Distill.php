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

use Distill\Extractor\ExtractorInterface;
use Distill\Strategy\StrategyInterface;
use Distill\Format\FormatInterface;
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
     * Container.
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->container->register(new ContainerProvider());
    }

    /**
     * Extracts the compressed file into the given path.
     * @param string                 $file   Compressed file
     * @param string                 $path   Destination path
     * @param Format\FormatInterface $format
     *
     * @return bool Returns TRUE when successful, FALSE otherwise
     */
    public function extract($file, $path, FormatInterface $format = null)
    {
        if (null === $format) {
            $format = $this->container['distill.format_guesser']->guess($file);
        }

        return $this->container['distill.extractor.extractor']->extract($file, $path, $format);
    }

    /**
     * Gets the file chooser.
     *
     * @return Chooser
     */
    public function getChooser()
    {
        return $this->container['distill.chooser'];
    }

}
