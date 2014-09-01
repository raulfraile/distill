# Distill: Smart compressed files extractor for PHP

[![Build Status](https://secure.travis-ci.org/raulfraile/ladybug.png)](http://travis-ci.org/raulfraile/distill)
[![Latest Stable Version](https://poser.pugx.org/raulfraile/distill/v/stable.png)](https://packagist.org/packages/raulfraile/distill)
[![Total Downloads](https://poser.pugx.org/raulfraile/distill/downloads.png)](https://packagist.org/packages/raulfraile/distill)
[![Latest Unstable Version](https://poser.pugx.org/raulfraile/distill/v/unstable.png)](https://packagist.org/packages/raulfraile/distill)

Distill extracts files from compressed archives.

Features:

* Extract files from `bz2`, `gz`, `phar`, `rar`, `tar`, `tar.bz2`, `tar.gz`, `tar.xz`, `7z`, `xz`
and `zip` archives.
* Different uncompression methods under the hood: PHP extensions and command line binaries.
* Strategy to choose the right file in case there are more than one available format. Strategies can be
based on minimizing bandwidth or optimizing uncompression speed.

## Installation

The recommended way to install Distill is through [Composer](http://packagist.org/about-composer). Just
create a `composer.json` file for your project:

[![Latest Stable Version](https://poser.pugx.org/raulfraile/distill/v/stable.png)](https://packagist.org/packages/raulfraile/ladybug)
[![Latest Unstable Version](https://poser.pugx.org/raulfraile/distill/v/unstable.png)](https://packagist.org/packages/raulfraile/ladybug)


``` json
{
    "require": {
        "raulfraile/distill": "*"
    }
}
```
To actually install Distill in your project, download the composer binary and run it:

``` bash
wget http://getcomposer.org/composer.phar
# or
curl -O http://getcomposer.org/composer.phar

php composer.phar install
```

## Example

```php
use Distill\Distill;
use Distill\File;
use Distill\Format\Zip;

$extractor = new Distill();

$file = new File(__DIR__.'/../tests/files/file_ok.zip', new Zip());

$extractor->extract($file, __DIR__ . '/extract');
```

## Formats

### Format support

* `bz2`: `bzip2` unix command.
* `gz`: `gzip` unix command.
* `phar`: `PHAR` extension.
* `rar`: `unrar` unix command and `rar` extension.
* `tar`: `tar` unix command, `Archive_Tar` package and `PHAR` extension.
* `tar.bz2`: `tar` unix command, `Archive_Tar` package and `PHAR` extension.
* `tar.gz`: `tar` unix command, `Archive_Tar` package and `PHAR` extension.
* `tar.xz`: `tar` unix command.
* `7z`: `7z` unix command
* `xz`: `xz` unix command
* `zip`: `tar` unix command and `zip` extension.

## Strategies

Distill allows to choose one format in case there are many available. For example, it can be
useful for installers that want to reduce the bandwidth usage trying to choose compression formats
with higher compression ratio and available in the client machine.

The library provides two strategies (more can be added):

* Minimum size (default): Choose files with higher compression ratio.
* Uncompression speed: Choose files which are faster to uncompress.

```php
use Distill\Distill;

$extractor = new Distill();

$extractor->addFile('test.tar.gz');
$extractor->addFile('test.zip');
$extractor->addFile('test.tar.xz');

$preferredFile = $extractor->getPreferredFile();

echo $preferredFile->getPath(); // test.tar.xz
```

```php
use Distill\Distill;
use Distill\Strategy\UncompressionSpeed;

$strategy = new UncompressionSpeed();
$extractor = new Distill(null, $strategy);

$extractor->addFile('test.tar.gz');
$extractor->addFile('test.zip');
$extractor->addFile('test.tar.xz');

$preferredFile = $extractor->getPreferredFile();

echo $preferredFile->getPath(); // test.tar.gz
```

## Command line tool

If you are looking for a command line tool to extract compressed files check out [distill-cli](https://github.com/raulfraile/distill-cli), which uses this library:

```
$ distill-cli extract archive.tar.gz path/
```

## Credits

* Raul Fraile ([@raulfraile](https://twitter.com/raulfraile))
* [All contributors](https://github.com/raulfraile/distill/contributors)

## License

Distill is released under the MIT License. See the bundled LICENSE file for details.
