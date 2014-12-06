<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Method\Native;

use Distill\Exception\CorruptedFileException;
use Distill\Exception\FormatNotSupportedInMethodException;
use Distill\Exception\MethodNotSupportedException;
use Distill\Format\FormatInterface;
use Distill\Format\Tar;
use Distill\Method\AbstractMethod;
use Distill\Method\MethodInterface;

/**
 * Extracts files from tar archives natively from PHP.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class TarExtractor extends AbstractMethod
{

    const TYPE_FILE = 0;
    const TYPE_HARD_LINK = 1;
    const TYPE_SYMBOLIC_LINK = 2;
    const TYPE_CHARACTER_SPECIAL = 3;
    const TYPE_BLOCK_SPECIAL = 4;
    const TYPE_DIRECTORY = 5;
    const TYPE_FIFO = 6;
    const TYPE_CONTIGUOUS_FILE = 7;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        if (!$this->isSupported()) {
            throw new MethodNotSupportedException($this);
        }

        @mkdir($target);

        return $this->extractTarFile($file, $target);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getClass()
    {
        return get_class();
    }

    /**
     * Reads a block header and returns an associative array with the headers.
     * @param resource $fileHandler File handler.
     * @param string   $filename    Filename.
     *
     * @throws CorruptedFileException
     *
     * @return array
     */
    protected function readBlockHeader($fileHandler, $filename)
    {
        $data = fread($fileHandler, 512);

        if (strlen($data) !== 512) {
            throw new CorruptedFileException($filename);
        }

        $headers = [
            'name' => trim(substr($data, 0, 100)),
            'mode' => (int) trim(substr($data, 100, 8)),
            'owner_id' => (int) trim(substr($data, 108, 8)),
            'group_id' => (int) trim(substr($data, 116, 8)),
            'size' => octdec(trim(substr($data, 124, 12))),
            'modification_time' => octdec(trim(substr($data, 136, 12))),
            'checksum' => trim(substr($data, 148, 8)),
            'type' => (int) trim(substr($data, 156, 1)),
            'link' => trim(substr($data, 157, 100)),
        ];

        // UStar format (Uniform Standard Tape ARchive)
        if (0x00 != ord($data[257]) && substr($data, 257, 5) === 'ustar') {
            $headers['ustar'] = [
                'version' => trim(substr($data, 263, 2)),
                'owner_name' => trim(substr($data, 265, 32)),
                'group_name' => trim(substr($data, 297, 32)),
                'device_major' => trim(substr($data, 329, 8)),
                'device_minor' => trim(substr($data, 237, 8)),
                'filename_prefix' => trim(substr($data, 345, 155))
            ];
        }

        return $headers;
    }

    /**
     * Reads a block of data.
     * @param resource $fileHandler File handler.
     * @param int      $size        Size in bytes.
     *
     * @return string Block contents.
     */
    protected function readBlockData($fileHandler, $size)
    {
        $blockSize = $size + (512 - ($size % 512));
        $data = fread($fileHandler, $size);
        fseek($fileHandler, $blockSize - $size, SEEK_CUR);

        return $data;
    }

    /**
     * Checks for end-of-file on a file pointer.
     * @param resource $fileHandler File handler.
     *
     * @return bool TRUE if it is end-of-file, FALSE otherwise.
     */
    protected function isEof($fileHandler)
    {
        $currentPosition = ftell($fileHandler);

        if (feof($fileHandler)) {
            return true;
        }

        $data = fread($fileHandler, 2);
        if (0 === strlen($data)) {
            return true;
        }

        fseek($fileHandler, $currentPosition, SEEK_SET);

        return false;
    }

    /**
     * Extracts the contents from a TAR file.
     * @param string $filename TAR file name.
     * @param string $target   Target path.
     *
     * @throws CorruptedFileException
     *
     * @return bool
     */
    protected function extractTarFile($filename, $target)
    {
        $fileHandler = fopen($filename, 'r');

        while (false === $this->isEof($fileHandler)) {
            $fields = $this->readBlockHeader($fileHandler, $filename);

            $name = $fields['name'];
            $type = $fields['type'];

            if (self::TYPE_FILE === $type) {
                $size = $fields['size'];
                if (0 === $size) {
                    continue;
                }

                // create the file
                file_put_contents($target . '/' . $name, $this->readBlockData($fileHandler, $size));

                continue;
            } elseif (self::TYPE_DIRECTORY === $type) {
                @mkdir($target . '/' . $name, 0777, true);
            }

        }

        fclose($fileHandler);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getUncompressionSpeedLevel(FormatInterface $format = null)
    {
        return MethodInterface::SPEED_LEVEL_LOWEST;
    }

    public function isFormatSupported(FormatInterface $format)
    {
        return $format instanceof Tar;
    }


}
