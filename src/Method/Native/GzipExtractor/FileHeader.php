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

use Distill\Exception\IO\Input\FileCorruptedException;

class FileHeader
{
    const MAGIC_NUMBER = 0x1f8b;

    const COMPRESSION_METHOD_DEFLATE = 8;

    const FLAGS_TEXT = 0x01;
    const FLAGS_HCRC = 0x02;
    const FLAGS_EXTRA = 0x04;
    const FLAGS_NAME = 0x08;
    const FLAGS_COMMENT = 0x10;

    protected $reservedFlags = [0x20, 0x40, 0x80];

    /**
     * Compression method.
     * @var int
     */
    protected $compressionMethod;

    /**
     * Flags.
     * @var int
     */
    protected $flags;

    /**
     * Extra flags.
     * @var int
     */
    protected $extraFlags;

    /**
     * Modification time.
     * @var \DateTime
     */
    protected $modificationTime;

    /**
     * Type of filesystem on which compression took place.
     * @var int
     */
    protected $operatingSystem;

    /**
     * CRC16 of the header.
     * @var string
     */
    protected $crc16;

    /**
     * Original filename
     * @var string
     */
    protected $originalFilename;

    /**
     * Comment.
     * @var string
     */
    protected $comment;

    /**
     * @var array
     */
    protected $extraSubfields;

    protected $extraData;

    /**
     * Creates an instance from a resource.
     * @param string   $filename    File name.
     * @param resource $fileHandler File handler.
     *
     * @throws FileCorruptedException
     *
     * @return FileHeader
     */
    public static function createFromResource($filename, $fileHandler)
    {
        $header = new FileHeader();

        $data = unpack("nmagicNumber/C1compressionMethod/C1flags/LmodificationTime/C1extraFlags/C1os", fread($fileHandler, 10));

        // check magic number and compression method
        if (self::MAGIC_NUMBER !== $data['magicNumber'] ||
            self::COMPRESSION_METHOD_DEFLATE !== $data['compressionMethod']) {
            throw new FileCorruptedException($filename);
        }

        $header
            ->setCompressionMethod($data['compressionMethod'])
            ->setFlags($data['flags'])
            ->setExtraFlags($data['extraFlags'])
            ->setModificationTimeFromUnixEpoch($data['modificationTime'])
            ->setOperatingSystem($data['os']);

        // if FLG.FEXTRA set
        if (($header->getFlags() & self::FLAGS_EXTRA) === self::FLAGS_EXTRA) {
            $extraFieldData = unpack("C2subfield/vlength", fread($fileHandler, 10));

            $header
                ->setExtraSubfields($extraFieldData['subfield1'], $extraFieldData['subfield2'])
                ->setExtraData(fread($fileHandler, $extraFieldData['length']));
        }

        // if FLG.FNAME set
        if (($header->getFlags() & self::FLAGS_NAME) === self::FLAGS_NAME) {
            $header->setOriginalFilename(self::readString($fileHandler));
        }

        // if FLG.FCOMMENT set
        if (($header->getFlags() & self::FLAGS_COMMENT) === self::FLAGS_COMMENT) {
            $header->setComment(self::readString($fileHandler));
        }

        // if FLG.FHCRC set
        if (($header->getFlags() & self::FLAGS_HCRC) === self::FLAGS_HCRC) {
            $crcData = unpack('vcrc', fread($fileHandler, 2));
            $header->setCrc16($crcData['crc']);
        }

        return $header;
    }

    /**
     * Reads a zero-terminated string.
     * @param resource $fileHandler File handler.
     *
     * @return string Zero-terminated string.
     */
    public static function readString($fileHandler)
    {
        $result = '';
        while (($char = fread($fileHandler, 1)) != "\0") {
            $result .= $char;
        }

        return $result;
    }

    /**
     * Gets the compression method.
     *
     * @return int Compression method.
     */
    public function getCompressionMethod()
    {
        return $this->compressionMethod;
    }

    /**
     * Sets the compression method.
     * @param int $compressionMethod Compression method.
     *
     * @return FileHeader
     */
    public function setCompressionMethod($compressionMethod)
    {
        $this->compressionMethod = $compressionMethod;

        return $this;
    }

    /**
     * Gets the flags.
     *
     * @return int Flags.
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Sets the flags.
     * @param int $flags Flags.
     *
     * @return FileHeader
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;

        return $this;
    }

    /**
     * Gets the modification time.
     *
     * It is the most recent modification time of the original file compressed. If the
     * compressed data did not come from a file, it is set to the time at which
     * compression started.
     *
     * @return \DateTime
     */
    public function getModificationTime()
    {
        return $this->modificationTime;
    }

    /**
     * Sets the modification time.
     * @param \DateTime $modificationTime Modification time.
     *
     * @return FileHeader
     */
    public function setModificationTime(\DateTime $modificationTime)
    {
        $this->modificationTime = $modificationTime;

        return $this;
    }

    /**
     * Sets the modification time from Unix Epoch.
     * @param int $modificationTime Seconds since 00:00:00 GMT, Jan. 1, 1970.
     *
     * @return FileHeader
     */
    public function setModificationTimeFromUnixEpoch($modificationTime)
    {
        $this->modificationTime = \DateTime::createFromFormat('U', $modificationTime);

        return $this;
    }

    /**
     * Gets the type of filesystem on which compression took place.
     *
     * This may be useful in determining end-of-line convention for text files.
     * Currently, defined values are:
     *  - 0 - FAT filesystem (MS-DOS, OS/2, NT/Win32)
     *  - 1 - Amiga
     *  - 2 - VMS (or OpenVMS)
     *  - 3 - Unix
     *  - 4 - VM/CMS
     *  - 5 - Atari TOS
     *  - 6 - HPFS filesystem (OS/2, NT)
     *  - 7 - Macintosh
     *  - 8 - Z-System
     *  - 9 - CP/M
     *  - 10 - TOPS-20
     *  - 11 - NTFS filesystem (NT)
     *  - 12 - QDOS
     *  - 13 - Acorn RISCOS
     *  - 255 - unknown
     *
     * @return int Type of filesystem.
     */
    public function getOperatingSystem()
    {
        return $this->operatingSystem;
    }

    /**
     * Sets the type of filesystem on which compression took place.
     * @param int $operatingSystem Type of filesystem.
     *
     * @return FileHeader
     */
    public function setOperatingSystem($operatingSystem)
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    /**
     * Gets the CRC16 value of the header.
     *
     * @return string CRC16 of the header.
     */
    public function getCrc16()
    {
        return $this->crc16;
    }

    /**
     * Sets the CRC16 value of the header.
     * @param string $crc16 CRC16 value
     *
     * @return FileHeader
     */
    public function setCrc16($crc16)
    {
        $this->crc16 = $crc16;

        return $this;
    }

    /**
     * Gets the original name of the compressed file.
     *
     * @return string Original name.
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Sets the original name of the compresses file.
     * @param string $originalFilename Original name.
     *
     * @return FileHeader
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * Gets the comment included in the file.
     *
     * @return string Comment.
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment included in the file.
     * @param string $comment Comment.
     *
     * @return FileHeader
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Gets the extra flags.
     *
     * @return int Extra flags.
     */
    public function getExtraFlags()
    {
        return $this->extraFlags;
    }

    /**
     * Sets the extra flags.
     * @param int $extraFlags Extra flags.
     *
     * @return FileHeader
     */
    public function setExtraFlags($extraFlags)
    {
        $this->extraFlags = $extraFlags;

        return $this;
    }

    /**
     * Gets the extra subfields (SI1 and SI2).
     *
     * @return array Extra subfields.
     */
    public function getExtraSubfields()
    {
        return $this->extraSubfields;
    }

    /**
     * Sets the extra subfields (SI1 and SI2).
     * @param string $si1 SI1 subfield.
     * @param string $si2 SI2 subfield.
     *
     * @return FileHeader
     */
    public function setExtraSubfields($si1, $si2)
    {
        $this->extraSubfields = [$si1, $si2];

        return $this;
    }

    /**
     * Gets the extra data.
     *
     * @return string Extra data.
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * Sets extra data.
     * @param string $extraData Extra data
     *
     * @return FileHeader
     */
    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;

        return $this;
    }
}
