<?php

namespace Distill\Format;


class TarBz2 implements FormatInterface
{

    public function getPriority()
    {
        return 3;
    }

}
