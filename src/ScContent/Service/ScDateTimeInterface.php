<?php
/**
 * ScContent (https://github.com/dphn/ScContent)
 *
 * @author    Dolphin <work.dolphin@gmail.com>
 * @copyright Copyright (c) 2013 ScContent
 * @link      https://github.com/dphn/ScContent
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ScContent\Service;

/**
 * @author Dolphin <work.dolphin@gmail.com>
 */
interface ScDateTimeInterface
{
    /**
     * @param string $name DateTimeZone identifier
     * @return string Old DateTimeZone identifier
     */
    function setTimeZone($name);

    /**
     * @return string Current DateTimeZone identifier
     */
    function getTimeZone();

    /**
     * @param boolean $new optional If you need to get the updated value of timestamp
     * @return integer DateTime stamp (in Unix format) for current locale
     */
    function stamp($new);

    /**
     * @return integer GMT offset of the current locale
     */
    function offset();

    /**
     * @param boolean $new optional If you need to get the updated value of timestamp
     * @return GMT (in Unix format)
     */
    function gmStamp($new);

    /**
     * @param integer $stamp optional GMT (in Unix format)
     * @param boolean $new optional If you need to get the updated value of timestamp
     * @return DataTime stamp (in Unix format) for current locale
     */
    function fromGm($stamp, $new);
}
