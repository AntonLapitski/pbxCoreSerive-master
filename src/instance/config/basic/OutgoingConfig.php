<?php

namespace app\src\instance\config\basic;

use app\src\instance\local_presents\LocalPresents;

/**
 * Class OutgoingConfig
 * Исходящий конфиг
 * @property LocalPresents|string $local_presents
 * @property string $phone
 * @package app\src\instance\config\basic
 */
abstract class OutgoingConfig extends BasicConfig
{
    /**
     * локальное содержимое
     *
     * @var LocalPresents|string
     */
    protected LocalPresents|string $local_presents;

    /**
     * телефон
     *
     * @var string
     */
    protected string $phone;

    /**
     * установить интеграционные данные
     *
     * @param array|null $data
     */
    public function setIntegrationData(array $data = null)
    {
        if (isset($data['pipeline']))
            self::studioNumber(['pipeline' => $data['pipeline']]);
    }

    /**
     * студийный номер
     *
     * @param array $config
     */
    protected function studioNumber(array $config = []): void
    {
        if (isset($this->local_presents))
            $sid = $this->local_presents->getCallerNumberSid($config['pipeline'] ?? null);
        else if (isset($this->phone))
            $sid = $this->phone;

        $this->studioNumber = isset($sid) ?
            $this->settings->twilio->studio_number_list->get('sid', $sid) :
            $this->settings->twilio->studio_number_list->get('id', 0);
    }

    /**
     * установить сво-во
     *
     * @param $fieldName
     * @param $sid
     */
    protected function setProperty($fieldName, $sid): void
    {
        if ('phone' === $fieldName)
            $this->phone = $sid;
        if ('local_presents' === $fieldName)
            $this->local_presents = new LocalPresents($sid, $this->settings->event->request->To);
        else
            parent::setProperty($fieldName, $sid);
    }

}