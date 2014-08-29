<?php

namespace Distill\Format;


class Tar implements FormatInterface
{

    public function getPriority()
    {
        return 1;
    }

}
