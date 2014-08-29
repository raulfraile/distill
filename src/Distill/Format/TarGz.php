<?php

namespace Distill\Format;


class TarGz implements FormatInterface
{

    public function getPriority()
    {
        return 2;
    }

}
