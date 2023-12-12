<?php


namespace app\src\instance\config\src\timetable;


/**
 * Class Timetable
 * @package app\src\instance\config\src\timetable
 */
class Timetable
{
    /**
     *
     */
    const OFFTIME = 'offtime';
    public string $status;
    protected string $weekday;
    protected int $hour;
    protected int $minute;
    protected int $month;
    protected int $dayOfMonth;
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
     * @throws \Exception
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
     * @param $schedule
     */
    protected function withStatusForCurrentSchedule($schedule): void
    {
        $this->status = self::OFFTIME;
        foreach ($schedule as $name => $timeTable)
            if ($this->isTimeInCurrentSchedule($timeTable[$this->weekday] ?? false))
                $this->status = $name;
    }

    /**
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
     * @param $timeInterval
     * @return bool
     */
    protected function isTimeInCurrentInterval($timeInterval): bool
    {
        return ($this->isAfterFrom($timeInterval['from'])
            && $this->isBeforeTo($timeInterval['to']));
    }

    /**
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



