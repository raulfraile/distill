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
use Distill\Format\Simple\Gz;
use Distill\Method\AbstractMethod;
use Distill\Method\MethodInterface;
use Distill\Method\Native\GzipExtractor\BitReader;
use Distill\Method\Native\GzipExtractor\FileHeader;
use Distill\Method\Native\GzipExtractor\HuffmanTree;

/**
 * Extracts files from gzip archives natively from PHP.
 *
 * @author Raul Fraile <raulfraile@gmail.com>
 */
class GzipExtractor extends AbstractMethod
{
    const COMPRESSION_TYPE_NON_COMPRESSED = 0x00;
    const COMPRESSION_TYPE_FIXED_HUFFMAN = 0x01;
    const COMPRESSION_TYPE_DYNAMIC_HUFFMAN = 0x02;

    const HLIT_BITS = 5;
    const HDIST_BITS = 5;
    const HCLEN_BITS = 4;

    const HLIT_INITIAL_VALUE = 257;
    const HDIST_INITIAL_VALUE = 1;
    const HCLEN_INITIAL_VALUE = 4;

    protected $codeLenghtsOrders = [
        16, 17, 18, 0, 8, 7, 9, 6, 10, 5,
        11, 4, 12, 3, 13, 2, 14, 1, 15,
    ];

    protected $distanceBase = [
        1, 2, 3, 4, 5, 7, 9, 13, 17, 25, 33, 49, 65, 97,
        129, 193, 257, 385, 513, 769, 1025, 1537, 2049,
        3073, 4097, 6145, 8193, 12289, 16385, 24577,
    ];

    protected $lengthBase = [
        3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 15, 17,
        19, 23, 27, 31, 35, 43, 51, 59, 67, 83,
        99, 115, 131, 163, 195, 227, 258,
    ];

    /**
     * {@inheritdoc}
     */
    public function extract($file, $target, FormatInterface $format)
    {
        $this->checkSupport($format);

        $this->getFilesystem()->mkdir($target);

        return $this->extractGzipFile($file, $target);
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
        return $format instanceof Gz;
    }

    /**
     * Extracts the contents from a GZIP file.
     * @param string $filename GZIP file name.
     * @param string $target   Target path.
     *
     * @throws Exception\IO\Input\FileCorruptedException
     *
     * @return bool
     */
    protected function extractGzipFile($filename, $target)
    {
        $fileHandler = fopen($filename, 'rb');

        $bitReader = new BitReader($fileHandler);

        // read file header
        try {
            $fileHeader = FileHeader::createFromResource($filename, $fileHandler);
        } catch (Exception\IO\Input\FileCorruptedException $e) {
            throw $e;
        }

        // read compressed blocks
        $result = '';
        $isBlockFinal = false;

        while (false === $isBlockFinal) {
            $isBlockFinal = 1 === $bitReader->read(1);
            $compressionType = $bitReader->read(2);

            if (self::COMPRESSION_TYPE_NON_COMPRESSED === $compressionType) {
                // no compression
                $data = unpack("vlength/vlengthOneComplement", fread($fileHandler, 4));

                $result .= fread($fileHandler, $data['length']);
            } else {
                // compression
                if (self::COMPRESSION_TYPE_FIXED_HUFFMAN === $compressionType) {
                    list($literalsTree, $distancesTree) = $this->getFixedHuffmanTrees();
                } elseif (self::COMPRESSION_TYPE_DYNAMIC_HUFFMAN === $compressionType) {
                    list($literalsTree, $distancesTree) = $this->getDynamicHuffmanTrees($bitReader);
                } else {
                    throw new Exception\IO\Input\FileCorruptedException($filename);
                }

                $result .= $this->uncompressCompressedBlock($literalsTree, $distancesTree, $bitReader, $result);
            }

            $literalsTree = null;
            $distancesTree = null;
        }

        // check crc32
        $footer = unpack('Vcrc/Visize', fread($fileHandler, 8));
        if ($footer['crc'] !== crc32($result)) {
            throw new Exception\IO\Input\FileCorruptedException($filename);
        }

        fclose($fileHandler);

        // write file
        $outputFilename = $fileHeader->getOriginalFilename();
        if (empty($outputFilename)) {
            $outputFilename = pathinfo($filename, PATHINFO_FILENAME);
        }
        $location = $target.DIRECTORY_SEPARATOR.$outputFilename;
        file_put_contents($location, $result);

        return true;
    }

    /**
     * Uncompresses a compressed block (fixed or dynamic Huffman) and saves the result in the
     * $result input variable.
     * @param HuffmanTree $literalsTree  Literals Huffman tree.
     * @param HuffmanTree $distancesTree Distances Huffman tree.
     * @param BitReader   $bitReader     Bit reader.
     * @param string      $window        Window.
     *
     * @return bool|string
     */
    protected function uncompressCompressedBlock(HuffmanTree $literalsTree, HuffmanTree $distancesTree, BitReader $bitReader, $window)
    {
        $endOfBlock = false;
        $result = '';
        $resultLength = 0;
        while (false === $endOfBlock) {
            $decoded = $literalsTree->findNextSymbol($bitReader);
            if (false === $decoded) {
                return false;
            }

            if (256 === $decoded) {
                $endOfBlock = true;
            } elseif ($decoded < 256) {
                $result .= chr($decoded);
                $resultLength++;
            } else {
                $lengthExtraBits = $this->getExtraLengthBits($decoded);
                $lengthExtra = 0;
                if ($lengthExtraBits > 0) {
                    $lengthExtra = $bitReader->read($lengthExtraBits);
                }

                $distance = $distancesTree->findNextSymbol($bitReader);
                $distanceExtra = $bitReader->read($this->getExtraDistanceBits($distance));

                $d = $this->distanceBase[$distance] + $distanceExtra;
                $l = $this->lengthBase[$decoded - 257] + $lengthExtra;

                if ($d <= $resultLength) {
                    $concat = substr($result, -1 * $d, $l);
                } else {
                    $concat = substr($window.$result, -1 * $d, $l);
                }

                $concatLength = strlen($concat);
                if ($concatLength < $l) {
                    // repeat last x character y times
                    $concat .= substr(str_repeat($concat, $l - $concatLength), 0, $l - $concatLength);
                }

                $result .= $concat;
                $resultLength += $concatLength;
            }
        }

        return $result;
    }

    /**
     * Creates the Huffman codes for literals and distances for fixed Huffman compression.
     *
     * @return HuffmanTree[] Literals tree and distances tree.
     */
    protected function getFixedHuffmanTrees()
    {
        return [
            HuffmanTree::createFromLengths(array_merge(
                array_fill_keys(range(0, 143), 8),
                array_fill_keys(range(144, 255), 9),
                array_fill_keys(range(256, 279), 7),
                array_fill_keys(range(280, 287), 8)
            )),
            HuffmanTree::createFromLengths(array_fill_keys(range(0, 31), 5))
        ];
    }

    /**
     * Creates the Huffman codes for literals and distances for dynamic Huffman compression.
     * @param BitReader $bitReader Bit reader.
     *
     * @return HuffmanTree[] Literals tree and distances tree.
     */
    protected function getDynamicHuffmanTrees(BitReader $bitReader)
    {
        $literalsNumber = $bitReader->read(self::HLIT_BITS) + self::HLIT_INITIAL_VALUE;
        $distancesNumber = $bitReader->read(self::HDIST_BITS) + self::HDIST_INITIAL_VALUE;
        $codeLengthsNumber = $bitReader->read(self::HCLEN_BITS) + self::HCLEN_INITIAL_VALUE;

        // code lengths
        $codeLengths = [];
        for ($i = 0; $i < $codeLengthsNumber; $i++) {
            $codeLengths[$this->codeLenghtsOrders[$i]] = $bitReader->read(3);
        }

        // create code lengths huffman tree
        $codeLengthsTree = HuffmanTree::createFromLengths($codeLengths);

        $i = 0;
        $literalAndDistanceLengths = [];
        $previousCodeLength = 0;
        while ($i < ($literalsNumber + $distancesNumber)) {
            $symbol = $codeLengthsTree->findNextSymbol($bitReader);

            if (false === $symbol) {
                return false;
            }

            if ($symbol >= 0 && $symbol <= 15) {
                // "normal" length
                $literalAndDistanceLengths[] = $symbol;
                $previousCodeLength = $symbol;

                $i++;
            } elseif ($symbol >= 16 && $symbol <= 18) {
                // repeat
                switch ($symbol) {
                    case 16:
                        $times = $bitReader->read(2) + 3;
                        $repeatedValue = $previousCodeLength;
                        break;
                    case 17:
                        $times = $bitReader->read(3) + 3;
                        $repeatedValue = 0;
                        break;
                    default:
                        $times = $bitReader->read(7) + 11;
                        $repeatedValue = 0;
                        break;
                }

                for ($j = 0; $j < $times; $j++) {
                    $literalAndDistanceLengths[] = $repeatedValue;
                }

                $i += $times;
            }
        }

        return [
            HuffmanTree::createFromLengths(array_slice($literalAndDistanceLengths, 0, $literalsNumber)),
            HuffmanTree::createFromLengths(array_slice($literalAndDistanceLengths, $literalsNumber))
        ];
    }

    /**
     * Gets the number of bits for the extra length.
     * @param int $value Length value.
     *
     * @return int Number of bits.
     */
    protected function getExtraLengthBits($value)
    {
        if (($value >= 257 && $value <= 260) || $value === 285) {
            return 0;
        } elseif ($value >= 261 && $value <= 284) {
            return (($value - 257) >> 2) - 1;
        } else {
            throw new Exception\InvalidArgumentException('value', 'Invalid value');
        }
    }

    /**
     * Gets the number of extra bits for the distance.
     * @param int $value Distance value.
     *
     * @return int Number of bits.
     */
    public function getExtraDistanceBits($value)
    {
        if ($value >= 0 && $value <= 1) {
            return 0;
        } elseif ($value >= 2 && $value <= 29) {
            return ($value >> 1) -1;
        } else {
            throw new Exception\InvalidArgumentException('value', 'Invalid value');
        }
    }
}
