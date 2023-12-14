<?php

namespace app\src\instance\config\call;

use app\exceptions\ContentNotFound;
use app\src\models\Voicemail;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\timetable\Timetable;

/**
 * Class CallIncomingConfig
 * @property array $incomingFlow
 * @package app\src\instance\config
 */
class IncomingConfig extends \app\src\instance\config\basic\IncomingConfig implements ConfigInterface
{
    /**
     * входящий поток
     *
     * @var array
     */
    protected array $incomingFlow;

    /**
     * вернуть поток
     *
     * @throws ContentNotFound
     * @return array
     */
    public function flow($status = null, string $flowSid = ''): array
    {
        if ($flowSid) {
            $this->setProperty('incomingFlow', $flowSid);
        }

        if (null === ($flow = $this->incomingFlow[$status ?? $this->timetable->status] ?? null))
            throw new ContentNotFound(
                sprintf(
                    'tt status %s was not found for current incoming flow',
                    $status ?? $this->timetable->status
                )
            );

        if ($sid = $flow['voicemail'] ?? null)
            if ($model = Voicemail::findOne(['sid' => $sid]))
                $flow['voicemail'] = $model->config['flow'];

        return $flow;
    }
}