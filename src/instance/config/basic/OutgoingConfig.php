<?php

namespace app\src\instance\config\basic;

use app\src\instance\local_presents\LocalPresents;

/**
 * Class OutgoingConfig
 * @package app\src\instance\config\basic
 */
abstract class OutgoingConfig extends BasicConfig
{
    protected LocalPresents|string $local_presents;
    protected string $phone;

    /**
     * @param array|null $data
     */
    public function setIntegrationData(array $data = null)
    {
        if (isset($data['pipeline']))
            self::studioNumber(['pipeline' => $data['pipeline']]);
    }

    /**
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