<?php

namespace Distill\Format;


class Phar implements FormatInterface
{

    public function getPriority()
    {
        return 1;
    }

}
