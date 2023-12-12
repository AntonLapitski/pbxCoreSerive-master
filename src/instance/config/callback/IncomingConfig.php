<?php


namespace app\src\instance\config\callback;

use app\exceptions\FlowSchemeNotFound;

/**
 * Class IncomingConfig
 * @package app\src\instance\config\callback
 */
class IncomingConfig extends \app\src\instance\config\basic\IncomingConfig
{
    protected array $callbackFlow;

    /**
     * @param null $status
     * @return array
     */
    public function flow($status = null): array
    {
        if (!$this->settings->log->model->isNewRecord)
            return (new OutgoingConfig($this->model, $this->settings))->get($this->studioNumber->number)->flow($status);

        if (null === ($flow = $this->callbackFlow[$status ?? $this->timetable->status] ?? null))
            throw new FlowSchemeNotFound('SchemeModel was not found');

        return $flow;
    }
}