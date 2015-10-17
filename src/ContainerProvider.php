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

use Distill\Extractor\Extractor;
use Distill\Method\MethodInterface;
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
    public function __construct(
        array $disabledMethods = [],
        array $disabledFormats = []
    ) {
        $methodsClasses = [
            Method\Command\Ar::getClass(),
            Method\Command\Bzip2::getClass(),
            Method\Command\Cabextract::getClass(),
            Method\Command\Cpio::getClass(),
            Method\Command\GnuGzip::getClass(),
            Method\Command\GnuTar::getClass(),
            Method\Command\Unrar::getClass(),
            Method\Command\Unshar::getClass(),
            Method\Command\Unzip::getClass(),
            Method\Command\X7Zip::getClass(),
            Method\Command\Xz::getClass(),
            Method\Command\Gnome\Gcab::getClass(),
            Method\Extension\Pear\ArchiveTar::getClass(),
            Method\Extension\Bzip2::getClass(),
            Method\Extension\Phar::getClass(),
            Method\Extension\PharData::getClass(),
            Method\Extension\Rar::getClass(),
            Method\Extension\Zip::getClass(),
            Method\Extension\Zlib::getClass(),
            Method\Native\TarExtractor::getClass(),
            Method\Native\GzipExtractor::getClass(),
        ];

        $formatsClasses = [
            Format\Simple\Ace::getClass(),
            Format\Simple\Ar::getClass(),
            Format\Simple\Arc::getClass(),
            Format\Simple\Arj::getClass(),
            Format\Simple\Bin::getClass(),
            Format\Simple\Bz2::getClass(),
            Format\Simple\Cab::getClass(),
            Format\Simple\Chm::getClass(),
            Format\Simple\Cpio::getClass(),
            Format\Simple\Deb::getClass(),
            Format\Simple\Dmg::getClass(),
            Format\Simple\Epub::getClass(),
            Format\Simple\Exe::getClass(),
            Format\Simple\Gz::getClass(),
            Format\Simple\Hfs::getClass(),
            Format\Simple\Img::getClass(),
            Format\Simple\Iso::getClass(),
            Format\Simple\Jar::getClass(),
            Format\Simple\Lzh::getClass(),
            Format\Simple\Lzma::getClass(),
            Format\Simple\Msi::getClass(),
            Format\Simple\Phar::getClass(),
            Format\Simple\Rar::getClass(),
            Format\Simple\Rpm::getClass(),
            Format\Simple\Shar::getClass(),
            Format\Simple\Tar::getClass(),
            Format\Simple\Wim::getClass(),
            Format\Simple\X7z::getClass(),
            Format\Simple\Xz::getClass(),
            Format\Simple\Zip::getClass(),

            Format\Composed\TarBz2::getClass(),
            Format\Composed\TarGz::getClass(),
            Format\Composed\TarXz::getClass(),

        ];

        $this->formats = [];
        foreach ($formatsClasses as $formatClass) {
            if (false === in_array($formatClass::getName(), $disabledFormats)) {
                $this->formats[] = $formatClass::getClass();
            }
        }

        $this->methods = [];
        foreach ($methodsClasses as $methodClass) {
            if (false === in_array($methodClass::getName(), $disabledMethods)) {
                $this->methods[] = $methodClass::getClass();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $this->registerFormats($container);
        $this->registerMethods($container);
        $this->registerStrategies($container);

        $container['format_guesser'] = $container->factory(function ($c) {
            return new FormatGuesser($this->getFormatsFromContainer($c));
        });

        $container['support_checker'] = $container->factory(function ($c) {
            return new SupportChecker($this->getMethodsFromContainer($c), $this->getFormatsFromContainer($c));
        });

        $container['chooser'] = $container->factory(function ($c) {
            return new Chooser(
                $c['support_checker'],
                $c['strategy.minimum_size'],
                $c['format_guesser'],
                $this->getMethodsFromContainer($c)
            );
        });

        $container['extractor.extractor'] = $container->factory(function ($c) {
            return new Extractor($this->getMethodsFromContainer($c), $c['support_checker']);
        });
    }

    /**
     * Registers the formats.
     * @param Container $container Container
     */
    protected function registerFormats(Container $container)
    {
        foreach ($this->formats as $formatClass) {
            $container['format.'.$formatClass::getName()] = $container->factory(function() use ($formatClass) {
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
        $orderedMethods = [];

        foreach ($this->methods as $methodClass) {
            /** @var MethodInterface $method */
            $method = new $methodClass();

            if ($method->isSupported()) {
                $container['method.'.$method->getName()] = function() use ($methodClass) {
                    return new $methodClass();
                };

                $orderedMethods[] = 'method.'.$method->getName();
            }
        }

        // order methods
        usort($orderedMethods, function ($methodName1, $methodName2) use ($container) {
            $value1 = ((int) $container[$methodName1]->isSupported()) + ($container[$methodName1]->getUncompressionSpeedLevel() / 10);
            $value2 = ((int) $container[$methodName2]->isSupported()) + ($container[$methodName2]->getUncompressionSpeedLevel() / 10);

            if ($value1 == $value2) {
                return 0;
            }

            return ($value1 > $value2) ? -1 : 1;
        });

        $container['method.__ordered'] = $orderedMethods;
    }

    protected function registerStrategies(Container $container)
    {
        $container['strategy.'.Strategy\MinimumSize::getName()] = $container->factory(function() {
            return new Strategy\MinimumSize();
        });
        $container['strategy.'.Strategy\UncompressionSpeed::getName()] = $container->factory(function() {
            return new Strategy\UncompressionSpeed();
        });
        $container['strategy.'.Strategy\Random::getName()] = $container->factory(function() {
            return new Strategy\Random();
        });
    }

    protected function getFormatsFromContainer(Container $container)
    {
        $formats = [];
        foreach ($container->keys() as $key) {
            if (0 === strpos($key, 'format.')) {
                $formats[] = $container[$key];
            }
        }

        return $formats;
    }

    protected function getMethodsFromContainer(Container $container)
    {
        $methods = [];
        foreach ($container['method.__ordered'] as $key) {
            $methods[] = $container[$key];
        }

        return $methods;
    }
}
