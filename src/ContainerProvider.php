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
            Method\Command\Bzip2::getClass(),
            Method\Command\Cabextract::getClass(),
            Method\Command\GnuGzip::getClass(),
            Method\Command\GnuTar::getClass(),
            Method\Command\Unrar::getClass(),
            Method\Command\Unzip::getClass(),
            Method\Command\x7zip::getClass(),
            Method\Command\Xz::getClass(),
            Method\Command\Gnome\Gcab::getClass(),
            Method\Extension\Pear\ArchiveTar::getClass(),
            Method\Extension\Phar::getClass(),
            Method\Extension\PharData::getClass(),
            Method\Extension\Rar::getClass(),
            Method\Extension\Zip::getClass(),
            Method\Native\TarExtractor::getClass(),
        ];

        $formatsClasses = [
            Format\Bz2::getClass(),
            Format\Cab::getClass(),
            Format\Epub::getClass(),
            Format\Gz::getClass(),
            Format\Jar::getClass(),
            Format\Phar::getClass(),
            Format\Rar::getClass(),
            Format\Tar::getClass(),
            Format\TarBz2::getClass(),
            Format\TarGz::getClass(),
            Format\TarXz::getClass(),
            Format\x7z::getClass(),
            Format\Xz::getClass(),
            Format\Zip::getClass(),
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

        $container['distill.format_guesser'] = $container->factory(function ($c) {
            return new FormatGuesser($this->getFormatsFromContainer($c));
        });

        $container['distill.support_checker'] = $container->factory(function ($c) {
            return new SupportChecker($this->getMethodsFromContainer($c), $this->getFormatsFromContainer($c));
        });

        $container['distill.chooser'] = $container->factory(function ($c) {
            return new Chooser(
                $c['distill.support_checker'],
                $c['distill.strategy.minimum_size'],
                $c['distill.format_guesser'],
                $this->getMethodsFromContainer($c)
            );
        });

        $container['distill.extractor.extractor'] = $container->factory(function ($c) {
            return new Extractor($this->getMethodsFromContainer($c), $c['distill.support_checker']);
        });
    }

    /**
     * Registers the formats.
     * @param Container $container Container
     */
    protected function registerFormats(Container $container)
    {
        foreach ($this->formats as $formatClass) {
            $container['distill.format.'.$formatClass::getName()] = $container->factory(function ($c) use ($formatClass) {
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
                $container['distill.method.'.$method->getName()] = function ($c) use ($methodClass) {
                    return new $methodClass();
                };

                $orderedMethods[] = 'distill.method.'.$method->getName();
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

        $container['distill.method.__ordered'] = $orderedMethods;
    }

    protected function registerStrategies(Container $container)
    {
        $container['distill.strategy.'.Strategy\MinimumSize::getName()] = $container->factory(function ($c) {
            return new Strategy\MinimumSize();
        });
        $container['distill.strategy.'.Strategy\UncompressionSpeed::getName()] = $container->factory(function ($c) {
            return new Strategy\UncompressionSpeed();
        });
        $container['distill.strategy.'.Strategy\Random::getName()] = $container->factory(function ($c) {
            return new Strategy\Random();
        });
    }

    protected function getFormatsFromContainer(Container $container)
    {
        $formats = [];
        foreach ($container->keys() as $key) {
            if (0 === strpos($key, 'distill.format.')) {
                $formats[] = $container[$key];
            }
        }

        return $formats;
    }

    protected function getMethodsFromContainer(Container $container)
    {
        $methods = [];
        foreach ($container['distill.method.__ordered'] as $key) {
            $methods[] = $container[$key];
        }

        return $methods;
    }
}
