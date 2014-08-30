<?php

namespace Distill\Format;


use Distill\Exception\ExtensionNotSupportedException;

interface FormatGuesserInterface
{

    /**
     * @param $path
     * @throws ExtensionNotSupportedException
     *
     * @return FormatInterface
     */
    public function guess($path);

}
