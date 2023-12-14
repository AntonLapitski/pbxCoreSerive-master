<?php


namespace app\src\pbx\checkpoint\strategy;


use app\src\models\Model;

/**
 * Class BasicCheckpoint
 * @property string $type
 * @property string $branch
 * @property mixed $step
 * @property string $timetable_status
 * @package app\src\pbx\checkpoint\strategy
 */
abstract class BasicCheckpoint extends Model
{
    /**
     * тип
     *
     * @var string
     */
    public string $type;

    /**
     * ветка
     *
     * @var string
     */
    public string $branch;

    /**
     * шаг
     *
     * @var mixed
     */
    public mixed $step;

    /**
     * статус расписание
     *
     * @var string
     */
    public string $timetable_status;

    /**
     * BasicCheckpoint constructor.
     * @param string $status
     * @param array $config
     */
    public function __construct(string $status, $config = [])
    {
        parent::__construct($config);
        $this->setTTStatus($status);
    }

    /**
     * установить статус
     *
     * @param $status
     */
    private function setTTStatus($status): void
    {
        if (!($this->timetable_status ?? false))
            $this->timetable_status = $status;
    }
}