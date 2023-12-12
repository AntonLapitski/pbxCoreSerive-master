<?php

namespace app\src\instance\config\basic;

use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\blacklist\Blacklist;
use app\src\instance\config\src\timetable\Timetable;

/**
 * Class IncomingConfig
 * @package app\src\instance\config\basic
 */
abstract class IncomingConfig extends BasicConfig implements ConfigInterface
{
    public Timetable $timetable;
    public Blacklist $blacklist;

    /**
     * @param array $config
     * @return mixed|void
     */
    protected function studioNumber(array $config = [])
    {
        $this->studioNumber = $this->settings->twilio->studio_number_list->get('number', $config['number']);
    }
}