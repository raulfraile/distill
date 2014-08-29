<?php

namespace Distill\Format;


class Xz implements FormatInterface
{

    public function getPriority()
    {
        return 10;
    }

}
