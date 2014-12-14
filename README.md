# Distill: Smart compressed files extractor for PHP

[![Build Status](https://secure.travis-ci.org/raulfraile/distill.png)](http://travis-ci.org/raulfraile/distill)
[![Latest Stable Version](https://poser.pugx.org/raulfraile/distill/v/stable.png)](https://packagist.org/packages/raulfraile/distill)
[![Total Downloads](https://poser.pugx.org/raulfraile/distill/downloads.png)](https://packagist.org/packages/raulfraile/distill)
[![Latest Unstable Version](https://poser.pugx.org/raulfraile/distill/v/unstable.png)](https://packagist.org/packages/raulfraile/distill)

Distill extracts files from compressed archives.

Features:

* Extract files from `ar`, `bz2`, `cab`, `chm`, `cpio`, `deb`, `dmg`, `epub`, `gz`, `phar`, `rar`, `shar`, `tar`, `tar.bz2`, `tar.gz`, `tar.xz`, `wim`, `7z`, `xz`, `Z` and `zip` archives.
* Different decompression methods under the hood: PHP extensions, command line binaries, third-party libraries and even fallback methods in plain PHP.
* Strategy to choose the right file in case there are more than one available format. Strategies can be
based on minimizing bandwidth or optimizing decompression speed.

## Installation

The recommended way to install Distill is through [Composer](http://packagist.org/about-composer). Require the `raulfraile/distill` package into your `composer.json` file:

[![Latest Stable Version](https://poser.pugx.org/raulfraile/distill/v/stable.png)](https://packagist.org/packages/raulfraile/distill)
[![Latest Unstable Version](https://poser.pugx.org/raulfraile/distill/v/unstable.png)](https://packagist.org/packages/raulfraile/distill)


``` json
{
    "require": {
        "raulfraile/distill": "@stable"
    }
}
```

**Protip**: you should browse the [raulfraile/distill](https://packagist.org/packages/raulfraile/distill) page to choose a stable version to use, avoid the `@stable` meta constraint.

Otherwise, install the library and setup the autoloader yourself.

## Example

```php
use Distill\Distill;

$distill = new Distill();
$distill->extract(__DIR__ . '/../tests/files/file_ok.zip', __DIR__ . '/extract');
```

## Formats

## Strategies

Distill allows to choose one format in case there are many available. For example, it can be
useful for installers that want to reduce the bandwidth usage trying to choose compression formats
with higher compression ratio and available in the client machine.

The library provides three strategies (more can be added):

* Minimum size (default): Choose files with higher compression ratio.
* Uncompression speed: Choose files which are faster to uncompress.
* Random: Gets a random file which can be uncompressed by the system.

```php
use Distill\Distill;

$distill = new Distill();

$preferredFile = $distill
    ->getChooser()
    ->setStrategy(new \Distill\Strategy\MinimumSize())
    ->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.zip')
    ->addFile('http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.tgz')
    ->getPreferredFile();

echo $preferredFile; // http://get.symfony.com/Symfony_Standard_Vendors_2.5.3.tgz
```

```php
use Distill\Distill;

$distill = new Distill();

$preferredFile = $distill
    ->getChooser()
    ->setStrategy(new \Distill\Strategy\UncompressionSpeed())
    ->addFile('test.phar')
    ->addFile('test.zip')
    ->getPreferredFile();

echo $preferredFile; // test.zip
``` 

## Command line tool

If you are looking for a command line tool to extract compressed files check out [distill-cli](https://github.com/raulfraile/distill-cli), which uses this library:

```
$ distill-cli extract archive.tar.gz path/
```

## Contributing

See [CONTRIBUTING](https://github.com/raulfraile/distill/blob/master/CONTRIBUTING.md) file.


## Running the Tests

Install the [Composer](http://getcomposer.org/) `dev` dependencies:

```
$ composer install --dev
``` 

Then, run the test suite using [PHPUnit](http://phpunit.de/):

```
$ phpunit
```

## Credits

* Raul Fraile ([@raulfraile](https://twitter.com/raulfraile))
* [All contributors](https://github.com/raulfraile/distill/contributors)

## License

Distill is released under the MIT License. See the bundled [LICENSE](https://github.com/raulfraile/distill/blob/master/LICENSE) file for details.
