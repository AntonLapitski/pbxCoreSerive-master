<?php


namespace app\src\pbx\checkpoint\strategy;


use app\src\models\Model;

/**
 * Class BasicCheckpoint
 * @package app\src\pbx\checkpoint\strategy
 */
abstract class BasicCheckpoint extends Model
{
    public string $type;
    public string $branch;
    public mixed $step;
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
     * @param $status
     */
    private function setTTStatus($status): void
    {
        if (!($this->timetable_status ?? false))
            $this->timetable_status = $status;
    }
}