<?php


namespace app\src\event;


/**
 * Class CallEvent
 * @package app\src\event
 */
class CallEvent extends Event implements EventInterface
{
    /**
     *
     */
    const SERVICE_INTERNAL = 'internal';
    /**
     *
     */
    const SERVICE_GATHER = 'gather';
    /**
     *
     */
    const SERVICE_REFER = 'refer';
    /**
     *
     */
    const SERVICE_MAIN = 'main';
    /**
     *
     */
    const SERVICE_OUTGOING = 'outgoing';

    /**
     * установить данные
     *
     * @param array $settings
     */
    public function setData(array $settings): void
    {
        parent::setData($settings);

        if ($this->isCallerHangup())
            $this->request->setDialCallData(\Yii::$app->twilio->getDialCallData($this->request->CallSid));
    }

    /**
     * было ли завсиание в звонке
     *
     * @return bool
     */
    private function isCallerHangup(): bool
    {
        return ('caller-hung-up' === ($this->request->Result ?? false));
    }

    /**
     * статус завершенности звонка
     *
     * @return bool
     */
    private function isCompletedCallStatus(): bool
    {
        return self::STATUS_COMPLETED === ($this->request->CallStatus ?? false);
    }

    /**
     * задать маршруту шаг
     *
     * @return bool
     */
    private function isRouteStep(): bool
    {
        return 'route' === $this->step;
    }

    /**
     * поставить статусу шаг
     *
     * @return bool
     */
    private function isStatusStep(): bool
    {
        return 'status' === $this->step;
    }
}