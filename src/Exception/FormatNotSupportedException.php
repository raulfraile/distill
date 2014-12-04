<?php

/*
 * This file is part of the Distill package.
 *
 * (c) Raul Fraile <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Distill\Exception;

use Distill\Format\FormatInterface;

class FormatNotSupportedException extends \Exception
{

    /**
     * Format.
     * @var FormatInterface
     */
    protected $format;

    /**
     * Constructor.
     * @param FormatInterface $format    Format
     * @param int             $code      Exception code
     * @param \Exception      $previous  Previous exception
     */
    public function __construct(FormatInterface $format, $code = 0, \Exception $previous = null)
    {
        $this->format = $format;

        $message = sprintf('Format "%s" is not supported', $format->getName());

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return FormatInterface
     */
    public function getFormat()
    {
        return $this->format;
    }

}
