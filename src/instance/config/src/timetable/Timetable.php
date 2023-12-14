<?php


namespace app\src\instance\config\src\timetable;


/**
 * Class Timetable
 * Класс расписание
 *
 * @property string $status
 * @property string $weekday
 * @property int $hour
 * @property int $minute
 * @property int $month
 * @property int $dayOfMonth
 * @property string $timeZone
 * @package app\src\instance\config\src\timetable
 */
class Timetable
{
    const OFFTIME = 'offtime';

    /**
     * статус
     *
     * @var string
     */
    public string $status;

    /**
     * день недели
     *
     * @var string
     */
    protected string $weekday;

    /**
     * час
     *
     * @var string
     */
    protected int $hour;

    /**
     * минуты
     *
     * @var string
     */
    protected int $minute;

    /**
     * месяц
     *
     * @var string
     */
    protected int $month;

    /**
     * день месяца
     *
     * @var string
     */
    protected int $dayOfMonth;

    /**
     * таймзона
     *
     * @var string
     */
    private string $timeZone;

    /**
     * Timetable constructor.
     * @param $timeZone
     */
    public function __construct($timeZone)
    {
        $this->timeZone = $timeZone;
        $this->setCurrentDate();
    }

    /**
     * установить дату
     *
     * @throws \Exception
     * @return void
     */
    private function setCurrentDate()
    {
        $dateTime = new \DateTime(
            'now',
            new \DateTimeZone($this->timeZone)
        );

        $this->hour = (int)$dateTime->format('G');
        $this->minute = (int)$dateTime->format('i');
        $this->weekday = strtolower($dateTime->format('D'));
        $this->month = (int)$dateTime->format('n');
        $this->dayOfMonth = (int)$dateTime->format('j');
    }

    /**
     * получить для текущего расписания
     *
     * @param \app\src\models\Timetable $schedule
     * @param $defaultTimezone
     * @return Timetable
     */
    public static function getForCurrentSchedule(\app\src\models\Timetable $schedule, $defaultTimezone): Timetable
    {
        $obj = new self( $schedule->settings['timezone'] ?? $defaultTimezone);
        $obj->withStatusForCurrentSchedule($schedule->scheme);
        return $obj;
    }

    /**
     * со статусом текущего расписния
     *
     * @param $schedule
     * @return void
     */
    protected function withStatusForCurrentSchedule($schedule): void
    {
        $this->status = self::OFFTIME;
        foreach ($schedule as $name => $timeTable)
            if ($this->isTimeInCurrentSchedule($timeTable[$this->weekday] ?? false))
                $this->status = $name;
    }

    /**
     * является ли время в текущем расписании
     *
     * @param $timeTable
     * @return bool
     */
    protected function isTimeInCurrentSchedule($timeTable): bool
    {
        if (false === $timeTable)
            return false;

        foreach ($timeTable as $timeInterval)
            if ($this->isTimeInCurrentInterval($timeInterval))
                return true;

        return false;
    }

    /**
     * является ли время в текущем интервале
     *
     * @param $timeInterval
     * @return bool
     */
    protected function isTimeInCurrentInterval($timeInterval): bool
    {
        return ($this->isAfterFrom($timeInterval['from'])
            && $this->isBeforeTo($timeInterval['to']));
    }

    /**
     * является ли после него
     *
     * @param $from
     * @return bool
     */
    protected function isAfterFrom($from): bool
    {
        if ($this->hour >= $from['h'])
            if ($this->minute >= $from['m'])
                return true;

        return false;
    }

    /**
     * перед тем
     *
     * @param $to
     * @return bool
     */
    protected function isBeforeTo($to): bool
    {
        if ($this->minute < $to['m']) {
            if ($this->hour <= $to['h'])
                return true;
        } else if ($this->hour < $to['h'])
            return true;

        return false;
    }
}



