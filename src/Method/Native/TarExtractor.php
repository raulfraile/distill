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

use Distill\Exception;
use Distill\Format\FormatInterface;
use Distill\Format\Simple\Tar;
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

    const SIZE_HEADER_BLOCK = 512;

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        $this->checkSupport($format);

        $this->getFilesystem()->mkdir($target);

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
     * @throws Exception\IO\Input\FileCorruptedException
     *
     * @return array
     */
    protected function readBlockHeader($fileHandler, $filename)
    {
        $data = fread($fileHandler, self::SIZE_HEADER_BLOCK);

        if (strlen($data) !== self::SIZE_HEADER_BLOCK) {
            throw new Exception\IO\Input\FileCorruptedException($filename);
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
                'filename_prefix' => trim(substr($data, 345, 155)),
            ];
        }

        if (0 === $headers['size'] && '' === $headers['name']) {
            $headers['is_empty'] = true;

            return $headers;
        }

        $headers['is_empty'] = false;

        $computedChecksum = $this->calculateChecksum($data);
        $headers['is_valid_checksum'] = $computedChecksum === octdec($headers['checksum']);

        return $headers;
    }

    /**
     * Calculates the checksum of the header block.
     *
     * The checksum is calculated by taking the sum of the unsigned byte values of the header
     * record with the eight checksum bytes taken to be ascii spaces (decimal value 32).
     *
     * @param string $headerBlock
     *
     * @return int
     */
    protected function calculateChecksum($headerBlock)
    {
        $checksum = 0;
        for ($i = 0; $i<self::SIZE_HEADER_BLOCK; $i++) {
            if ($i < 148 || $i >= 156) {
                $checksum += ord($headerBlock[$i]);
            } else {
                $checksum += 32;
            }
        }

        return $checksum;
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
     * @throws Exception\IO\Input\FileCorruptedException
     *
     * @return bool
     */
    protected function extractTarFile($filename, $target)
    {
        $fileHandler = fopen($filename, 'r');
        $hardlinks = [];

        while (false === $this->isEof($fileHandler)) {
            $fields = $this->readBlockHeader($fileHandler, $filename);

            // deal with empty blocks in sparse files
            if (true === $fields['is_empty']) {
                continue;
            }

            if (false === $fields['is_valid_checksum']) {
                throw new Exception\IO\Input\FileCorruptedException($filename);
            }

            $name = $fields['name'];
            $type = $fields['type'];

            $location = $target . DIRECTORY_SEPARATOR . $name;
            switch ($type) {
                case self::TYPE_FILE:
                    $this->doExtractFile($location, $fields, $fileHandler);
                    break;
                case self::TYPE_DIRECTORY:
                    $this->doExtractDir($location);
                    break;
                case self::TYPE_HARD_LINK:
                    $hardlinks[$location] = $fields;
                    break;
                case self::TYPE_SYMBOLIC_LINK:
                    $this->doExtractSymLink($location, $fields);
                    break;
            }
        }

        // This should be done after extract all the files
        foreach ($hardlinks as $location => $fields) {
            $this->doExtractHardLink($location, $fields, $target);
        }

        fclose($fileHandler);

        return true;
    }

    protected function doExtractFile($location, Array $fields, $fileHandler)
    {
        $this->getFilesystem()->mkdir(dirname($location));

        $size = $fields['size'];
        if (0 !== $size) {
            file_put_contents($location, $this->readBlockData($fileHandler, $size));
        } else {
            $this->getFilesystem()->touch($location);
        }
    }

    protected function doExtractDir($location)
    {
        $this->getFilesystem()->mkdir($location);
    }

    protected function doExtractHardLink($location, Array $fields, $target)
    {
        $origin = $target . DIRECTORY_SEPARATOR . $fields['link'];

        $onWindows = strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN';
        if ($onWindows) {
            $this->getFilesystem()->copy($origin, $location);
            return;
        }

        if (true !== @link($origin, $location)) {
            throw new \RuntimeException(sprintf(
                'Failed to create link from "%s" to "%s".', $origin, $location)
            );
        }
    }

    protected function doExtractSymLink($location, Array $fields)
    {
        $this->getFilesystem()->symlink($fields['link'], $location, true);
    }

    /**
     * {@inheritdoc}
     */
    public static function getUncompressionSpeedLevel(FormatInterface $format = null)
    {
        return MethodInterface::SPEED_LEVEL_LOWEST;
    }

    /**
     * {@inheritdoc}
     */
    public function isFormatSupported(FormatInterface $format)
    {
        return $format instanceof Tar;
    }
}
