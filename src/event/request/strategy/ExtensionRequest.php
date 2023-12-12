<?php

namespace app\src\event\request\strategy;

use app\src\event\Event;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class ExtensionRequest
 * @package app\src\event\request\strategy
 */
class ExtensionRequest extends CallRequest implements RequestInterface
{
    /**
     * @param $phone
     * @param $region
     * @return string
     */
    protected static function parsePhone($phone, $region): string
    {
        if (is_numeric($phone) && 3 < strlen($phone)) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            try {
                $swissNumberProto = $phoneUtil->parse($phone, $region);
                return $phoneUtil->format($swissNumberProto, PhoneNumberFormat :: E164);
            } catch (NumberParseException $e) {
                //TODO
            }
        }

        return str_replace(['client:'], '', $phone);
    }

    /**
     * @return string
     */
    protected function setDirection(): string
    {
        return Event::DIRECTION_OUTGOING;
    }
}