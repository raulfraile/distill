<?php

namespace Distill\Format;


class Gz implements FormatInterface
{

    public function getPriority()
    {
        return 1;
    }

}
