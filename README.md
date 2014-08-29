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
* Strategy to choose the right file in case there are more than 1 format available. Strategies can be
based on minimizing bandwidth or optimizing uncompression speed.

## Installation

The recommended way to install Distill is through [Composer](http://packagist.org/about-composer). Just
create a `composer.json` file for your project:

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

After running the `install` command, a new directory called 'vendor' will contain the Distill code, as well as all
the required dependencies.

Once added the autoloader you will have access to the library:

``` php
<?php

require 'vendor/autoload.php';
```

[![Latest Stable Version](https://poser.pugx.org/raulfraile/distill/v/stable.png)](https://packagist.org/packages/raulfraile/ladybug)
[![Latest Unstable Version](https://poser.pugx.org/raulfraile/distill/v/unstable.png)](https://packagist.org/packages/raulfraile/ladybug)

## Credits

* Raul Fraile ([@raulfraile](https://twitter.com/raulfraile))
* [All contributors](https://github.com/raulfraile/distill/contributors)

## License

Distill is released under the MIT License. See the bundled LICENSE file for details.