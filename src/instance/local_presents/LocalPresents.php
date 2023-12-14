<?php

namespace app\src\instance\local_presents;


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Class LocalPresents
 * класс локального представления
 *
 * @property ?array $config
 * @property array $target
 * @package app\src\instance\local_presents
 */
class LocalPresents
{
    /**
     * конфиг
     *
     * @var ?array
     */
    protected ?array $config;

    /**
     * задание
     *
     * @var array
     */
    protected array $target;

    /**
     * LocalPresents constructor.
     * @param $sid
     * @param $target
     */
    public function __construct($sid, $target)
    {
        $this->config = \app\src\models\LocalPresents::findOne(['sid' => $sid])->config ?? null;
        $this->target = $this->_target($target);
    }

    #[ArrayShape(['region_code' => "string", 'country_code' => "int|null"])]

    /**
     * установить регональные настройки
     *
     * @param $target
     * @return array
     */
    protected function _target($target): array
    {
        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $phoneNumberObject = $phoneNumberUtil->parse($target, null);
        return [
            'region_code' => substr(
                $phoneNumberUtil->getNationalSignificantNumber($phoneNumberObject),
                0,
                $phoneNumberUtil->getLengthOfNationalDestinationCode($phoneNumberObject)
            ),
            'country_code' => $phoneNumberObject->getCountryCode()
        ];
    }

    /**
     * забрать айди номер звонящего
     *
     * @param null $pipeline
     * @return bool|null
     */
    public function getCallerNumberSid($pipeline = null)
    {
        if ($pipeline)
            if ($sid = $this->config['pipeline_presents'][$pipeline] ?? null)
                return $sid;

        foreach ($this->config as $key => $config) {
            if (in_array($key, ['default_phone', 'pipeline_presents'])) continue;
            if ($sid = self::getFromGeo($config, $this->target))
                return $sid;
        }
        if (isset($this->config['default_phone']))
            return $this->config['default_phone'];

        return false;
    }

    #[Pure] protected static function getFromGeo($config, $target): ?string
    {
        if (isset($config['phone'])
            && self::isValidCountry($target['country_code'], $config['country_code'])
            && !self::isInRegionList($target['region_code'], $config['region_code_list'])
        )
            return $config['phone'];

        return null;
    }

    /**
     * является ли валидной страной
     *
     * @param $target_code
     * @param $country_code
     * @return bool
     */
    protected static function isValidCountry($target_code, $country_code): bool
        {
            return $target_code == $country_code;
        }


    /**
     * является ли в списке регионов
     *
     * @param $target
     * @param $list
     * @return bool
     */
    protected static function isInRegionList($target, $list): bool
        {
            return !is_bool(array_search($target, $list));
        }
    }