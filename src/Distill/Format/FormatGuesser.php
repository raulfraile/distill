<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Format;

use Distill\Exception\ExtensionNotSupportedException;

class FormatGuesser implements FormatGuesserInterface
{

    /**
     * Map of extensions and file formats
     * @var array
     */
    protected $extensionMap = array(
        'bz'      => 'Bz2',
        'bz2'     => 'Bz2',
        'gz'      => 'Gz',
        'phar'    => 'Phar',
        'rar'     => 'Rar',
        'tar'     => 'Tar',
        'tar.bz'  => 'TarBz2',
        'tar.bz2' => 'TarBz2',
        'tar.gz'  => 'TarGz',
        'tar.xz'  => 'TarXz',
        'tgz'     => 'TarGz',
        '7z'      => 'X7z',
        'xz'      => 'Xz',
        'Z'       => 'BZz2',
        'zip'     => 'Zip'
    );

    /**
     * {@inheritdoc}
     */
    public function guess($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $filename  = pathinfo($path, PATHINFO_FILENAME);

        if (in_array($extension, array('bz', 'bz2', 'gz', 'xz'))) {
            $subextension = pathinfo($filename, PATHINFO_EXTENSION);

            if ("" !== $subextension) {
                $extension = sprintf('%s.%s', $subextension, strtolower($extension));
            }
        }

        if (!array_key_exists($extension, $this->extensionMap)) {
            throw new ExtensionNotSupportedException($extension);
        }

        $className = 'Distill\\Format\\' . $this->extensionMap[$extension];

        return new $className();
    }

}
