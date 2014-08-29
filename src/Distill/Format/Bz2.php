<?php

namespace Distill\Format;


class Bz2 implements FormatInterface
{

    public function getPriority()
    {
        return 3;
    }

}
