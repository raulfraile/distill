<?php

namespace Distill\Format;


interface FormatInterface
{

    /**
     * Gets the priority of the format.
     *
     * @return integer Format priority
     */
    public function getPriority();

}
