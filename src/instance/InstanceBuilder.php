<?php


namespace app\src\instance;

use app\src\models\User;
use app\src\client\Client;
use app\src\event\EventInterface;
use app\src\instance\config\Config;
use app\src\instance\config\ConfigException;
use app\src\instance\config\ConfigInterface;
use app\src\instance\config\src\ConfigSettings;
use app\src\instance\config\src\integration\Integration;
use app\src\instance\data\Data;
use app\src\instance\log\Log;
use crmpbx\logger\Logger;

/**
 * @property EventInterface $event
 * @property Client $client
 * @property Log $log
 * @property Data $data
 * @property User $responsibleUser
 * @property ConfigInterface $config
 *
 */
trait InstanceBuilder
{
    public EventInterface $event;
    public Client $client;
    public Log $log;
    public ConfigInterface $config;
    public Data $data;
    public ?User $responsibleUser;

    private Logger $logger;

    /**
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws ConfigException
     */
    public function __construct(EventInterface $event, Client $client)
    {
        $this->event = $event;
        $this->client = $client;
        $this->event->setData(['country_code' => $this->client->company->country_code]);
        $this->log = new Log($this);
        $this->responsibleUser = $this->getResponsibleUser();
        $this->config = $this->getConfig();
        $this->data = new Data($this);
        if (!$this->isInBlackList())
            $this->setIntegrationData();

        $this->logger = \Yii::$app->logger;
        $this->logger->add('data', $this->data->getData());
        $this->logger->addInFile('data', $this->data->getData());
    }

    /**
     * @param null $target
     * @return User|null
     */
    protected function getResponsibleUser($target = null): ?User
    {
        if ($target)
            return $this->client->userList->get($target);

        if ($target = $this->log->model->checkpoint['target'] ?? null)
            return $this->client->userList->get($target);

        return null;
    }

    /**
     * @param \app\src\models\Config|null $model
     * @return ConfigInterface
     * @throws ConfigException
     */
    protected function getConfig(\app\src\models\Config $model = null): ConfigInterface
    {
        if (null === $model)
            throw new ConfigException('Call config was not found', 404);

        $settings = new ConfigSettings(
            $this->event,
            $this->client->twilio->settings,
            $this->log,
            $this->client->company->time_zone
        );

        $config = new Config($this->event, $settings);
        return $config->get($model);
    }

    /**
     *
     */
    protected function setIntegrationData(): void
    {
        if (isset($this->config->integration) && $this->config->integration instanceof Integration)
            try {
                $data = $this->config->integration->getData($this->data->integrationRequest(), $this->event->route);
                $this->data->setIntegrationData($data);
                $this->config->setIntegrationData($data);
                $this->log->model->integration_data = $data;
                if (false == $this->responsibleUser && $sid = $this->config->integration->getResponsibleUserSid())
                    $this->responsibleUser = $this->client->userList->get($sid);
            } catch (\Throwable $e) {
                $this->config->integration->model->is_active = false;
                $this->logger->addInFile('integration_request', $e);
            }
    }
}