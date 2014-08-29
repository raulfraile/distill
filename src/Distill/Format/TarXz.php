<?php

namespace Distill\Format;


class TarXz implements FormatInterface
{

    public function getPriority()
    {
        return 10;
    }

}
