<?php

namespace Distill\Extractor;

use Distill\Extractor\Adapter;
use Distill\File;

class Extractor implements ExtractorInterface
{

    /**
     * Available adapters.
     * @var Adapter\AdapterInterface[]
     */
    protected $adapters;

    /**
     * Constructor
     */
    public function __construct($adapters = null)
    {
        if (null === $adapters) {
            $adapters = array(
                new Adapter\Bz2Adapter(),
                new Adapter\GzAdapter(),
                new Adapter\PharAdapter(),
                new Adapter\RarAdapter(),
                new Adapter\TarAdapter(),
                new Adapter\TarBz2Adapter(),
                new Adapter\TarGzAdapter(),
                new Adapter\TarXzAdapter(),
                new Adapter\X7zAdapter(),
                new Adapter\XzAdapter(),
                new Adapter\ZipAdapter()
            );
        }

        $this->adapters = $adapters;
    }

    /**
     * {@inheritdoc}
     */
    public function addAdapter(Adapter\AdapterInterface $adapter)
    {
        $this->adapters[] = $adapter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function extract(File $file, $path)
    {
        $adaptersCount = count($this->adapters);
        $i = 0;
        $success = false;

        while (!$success && $i < $adaptersCount) {
            if ($this->adapters[$i]->supports($file)) {
                $success = $this->adapters[$i]->extract($file, $path);
            }

            $i++;
        }

        return $success;
    }


}
