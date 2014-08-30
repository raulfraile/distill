<?php

namespace Distill\Format;


use Distill\Exception\ExtensionNotSupportedException;

class FormatGuesser
{

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
        '7z'      => 'X7z',
        'xz'      => 'Xz',
        'Z'       => 'BZz2',
        'zip'     => 'Zip'
    );

    /**
     * @param $path
     * @throws \Distill\Exception\ExtensionNotSupportedException
     * @return FormatInterface
     */
    public function guess($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (!array_key_exists($extension, $this->extensionMap)) {
            throw new ExtensionNotSupportedException($extension);
        }

        $className = 'Distill\\Format\\' . $this->extensionMap[$extension];

        return new $className();
    }

}
