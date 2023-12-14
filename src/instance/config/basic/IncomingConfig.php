<?php

namespace app\src\instance\config\basic;

use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\blacklist\Blacklist;
use app\src\instance\config\src\timetable\Timetable;

/**
 * Class IncomingConfig
 * @property Timetable $timetable
 * @property Blacklist $blacklist
 * @package app\src\instance\config\basic
 */
abstract class IncomingConfig extends BasicConfig implements ConfigInterface
{
    /**
     * расписание
     *
     * @var Timetable
     */
    public Timetable $timetable;

    /**
     * черновой лист
     *
     * @var Blacklist
     */
    public Blacklist $blacklist;

    /**
     * студийный номер
     *
     * @param array $config
     * @return mixed|void
     */
    protected function studioNumber(array $config = [])
    {
        $this->studioNumber = $this->settings->twilio->studio_number_list->get('number', $config['number']);
    }
}