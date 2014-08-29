<?php

namespace Distill\Format;


class Zip implements FormatInterface
{

    public function getPriority()
    {
        return 1;
    }

}
