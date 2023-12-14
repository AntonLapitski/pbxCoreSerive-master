<?php

namespace app\src\event\request\strategy;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class TwilioRequestт с твилио сервисом
 * Запрос класс который взаимодействием
 *
 * @property string $AccountSid
 * @property string $Direction
 * @property string|array $From
 * @property string $To
 * @package app\src\event\request\strategy
 */
abstract class TwilioRequest extends Request implements RequestInterface
{
    /**
     * айди аккаунта
     *
     * @var string
     */
    public string $AccountSid;
    public string $Direction;
    public string|array $From;
    public string $To;

    /**
     * TwilioRequest constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->Direction = static::setDirection();
    }

    /**
     * установить направление звонка
     *
     * @return string
     */
    abstract protected function setDirection(): string;

    /**
     * поулчить направление зваонка
     *
     * @return string
     */
    public function getDirection(): string
    {
        return $this->Direction;
    }

    /**
     * установить кому и от кого направляется вызов
     *
     * @param $countryCode
     */
    public function setData($countryCode): void
    {
        $this->To = static::parsePhone($this->To, $countryCode);
        $this->From = static::parsePhone($this->From, $countryCode);
    }

    /**
     * отпарсить телефон
     *
     * @param $phone
     * @param $region
     * @return string
     */
    protected static function parsePhone($phone, $region): string
    {
        if ($ph = self::outgoingPhone($phone)) {
            if (3 < strlen($ph)) {
                $phoneUtil = PhoneNumberUtil::getInstance();
                try {
                    $swissNumberProto = $phoneUtil->parse($ph, $region);
                    return $phoneUtil->format($swissNumberProto, PhoneNumberFormat :: E164);
                } catch (NumberParseException $e) {
                    //TODO
                }
            }
            return $ph;
        }

        return $phone;
    }

    /**
     * исходящий телефон
     *
     * @param $phone
     * @return string|null
     */
    protected static function outgoingPhone($phone): ?string
    {
        $regSipUri = "/^sip:((.*)@(.*))/";
        preg_match($regSipUri, $phone, $matches);
        if ($matches)
            return trim($matches[2]);

        return null;
    }
}
