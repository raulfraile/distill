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

    const FLAGS_HCRC = 0x02;
    const FLAGS_EXTRA = 0x04;
    const FLAGS_NAME = 0x08;
    const FLAGS_COMMENT = 0x10;

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
     * Modification time.
     * @var \DateTime
     */
    protected $modificationTime;

    protected $operatingSystem;

    protected $crc32;

    protected $originalFilename;

    protected $comment;

    public static function createFromResource($filename, $fileHandler)
    {
        $header = new FileHeader();

        $data = unpack("nmagicNumber/C1compressionMethod/C1flags/LmodificationTime/C1extraFlags/C1os", fread($fileHandler, 10));

        if (self::MAGIC_NUMBER !== $data['magicNumber'] ||
            self::COMPRESSION_METHOD_DEFLATE !== $data['compressionMethod']) {
            throw new FileCorruptedException($filename);
        }

        $header
            ->setCompressionMethod($data['compressionMethod'])
            ->setFlags($data['flags'])
            ->setModificationTimeFromUnixEpoch($data['modificationTime'])
            ->setOperatingSystem($data['os']);

        // if FLG.FEXTRA set
        if (($header->getFlags() & self::FLAGS_EXTRA) === self::FLAGS_EXTRA) {
            $extraLength = fread($fileHandler, 2);
            $extraField = fread($fileHandler, $extraLength);
        }

        // if FLG.FNAME set
        if (($header->getFlags() & self::FLAGS_NAME) === self::FLAGS_NAME) {
            $originalFilename = '';
            while (($char = fread($fileHandler, 1)) != "\0") {
                $originalFilename .= $char;
            }

            $header->setOriginalFilename($originalFilename);
        }

        // if FLG.FCOMMENT set
        if (($header->getFlags() & self::FLAGS_COMMENT) === self::FLAGS_COMMENT) {
            $comment = '';
            while (($char = fread($fileHandler, 1)) != "\0") {
                $comment .= $char;
            }

            $header->setComment($comment);
        }

        // if FLG.FHCRC set
        if (($header->getFlags() & self::FLAGS_HCRC) === self::FLAGS_HCRC) {
            $crc16 = fread($fileHandler, 2);
        }

        return $header;
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
     * @param mixed $compressionMethod
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
     * @return mixed
     */
    public function getCrc32()
    {
        return $this->crc32;
    }

    /**
     * @param mixed $crc32
     *
     * @return FileHeader
     */
    public function setCrc32($crc32)
    {
        $this->crc32 = $crc32;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * @param mixed $originalFilename
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}
