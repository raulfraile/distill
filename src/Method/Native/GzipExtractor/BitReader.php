<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Native\GzipExtractor;

class BitReader
{
    /**
     * Current byte.
     * @var int
     */
    protected $currentByte;

    /**
     * Position of the current bit in the byte.
     * @var int
     */
    protected $currentBitPosition;

    /**
     * File handler.
     * @var resource
     */
    protected $fileHandler;

    /**
     * Constructor.
     * @param resource $fileHandler File handler.
     */
    public function __construct($fileHandler)
    {
        $this->fileHandler = $fileHandler;
        $this->currentByte = null;
        $this->currentBitPosition = 0;
    }

    /**
     * Reads a given number of bits.
     * @param int $bits Number of bits.
     *
     * @return int Bits.
     */
    public function read($bits)
    {
        return bindec($this->readBitStream($bits));
    }

    /**
     * Reads a given number of bits as a string.
     * @param int $bits Number of bits.
     *
     * @return string Bits.
     */
    public function readBitStream($bits)
    {
        $this->readByteIfNeeded();

        $result = '';
        for ($i = 0; $i < $bits; $i++) {
            $mask = pow(2, $this->currentBitPosition);
            $result = ((($this->currentByte & $mask) === $mask) ? '1' : '0').$result;

            $this->currentBitPosition++;
            if (($i + 1) < $bits) {
                $this->readByteIfNeeded();
            }
        }

        return $result;
    }

    /**
     * Reads a new byte if needed.
     */
    protected function readByteIfNeeded()
    {
        if ($this->checkIfNeedsToReadByte()) {
            $this->readByte();
        }
    }

    /**
     * Checks whether a new byte must be read.
     *
     * @return bool Returns TRUE if a new byte must be read, FALSE otherwise.
     */
    protected function checkIfNeedsToReadByte()
    {
        return null === $this->currentByte || $this->currentBitPosition > 7;
    }

    /**
     * Reads a byte from the file.
     *
     * @return bool
     */
    protected function readByte()
    {
        if (feof($this->fileHandler)) {
            return false;
        }

        $data = unpack('C1byte', fread($this->fileHandler, 1));
        $this->currentByte = $data['byte'];
        $this->currentBitPosition = 0;

        return true;
    }
}
