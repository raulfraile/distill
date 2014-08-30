<?php

namespace Distill\Format;


interface FormatInterface
{

    const LEVEL_LOWEST = 0;
    const LEVEL_LOW = 2;
    const LEVEL_MIDDLE = 5;
    const LEVEL_HIGH = 7;
    const LEVEL_HIGHEST = 10;

    /**
     * Gets the compression ratio level for the format.
     *
     * @return integer Compression ratio level (0: low, 10: high)
     */
    public function getCompressionRatioLevel();

    /**
     * Gets the uncompression speed level for the format.
     *
     * @return integer Uncompression speed level (0: low, 10: high)
     */
    public function getUncompressionSpeedLevel();

}
