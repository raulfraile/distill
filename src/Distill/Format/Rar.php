<?php

namespace Distill\Format;


class Rar implements FormatInterface
{

    public function getPriority()
    {
        return 1;
    }

}
