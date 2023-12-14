<?php

namespace app\src\instance;

use app\src\instance\config\Config;
use app\src\models\User;
use app\src\event\Event;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\integration\Integration;

/**
 * Class ExtensionInstance
 * @package app\src\instance
 */
class ExtensionInstance extends Instance
{
    /**
     * вернуть конфиг
     *
     * @param \app\src\models\Config|null $model
     * @return ConfigInterface
     * @throws config\ConfigException
     */
    protected function getConfig(\app\src\models\Config $model = null): ConfigInterface
    {
        if ($user = $this->client->userList->get($this->getTarget()))
            $model = $user->config;

        return parent::getConfig($model);
    }

    /**
     * получить ответсвеннного юзера
     *
     * @param null $target
     * @return User|null
     */
    protected function getResponsibleUser($target = null): ?User
    {
        return parent::getResponsibleUser($this->getTarget());
    }

    /**
     * получить задание
     *
     * @return string
     */
    private function getTarget(): string
    {
        return ($this->log->model->isNewRecord)
            ? $this->event->request->From
            : $this->log->model->checkpoint['from'];
    }

    /**
     * установить интеграционные данны
     *
     * @return void
     */
    protected function setIntegrationData(): void
    {
        if (!isset($this->config->integration) || !$this->config->integration instanceof Integration)
            return;

        $route = str_replace('extension', 'call', $this->event->route);

        if (in_array($this->event->step, [Event::STEP_INIT, Event::STEP_STATUS]))
            $this->config->integration->sendData($this->data->integrationRequest(), $route);
    }


}