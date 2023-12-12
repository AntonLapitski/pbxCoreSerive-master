<?php


namespace app\src\instance\config\src\integration;


use crmpbx\httpClient\Response;

/**
 * Class Integration
 * @package app\src\instance\config\src\integration
 */
class Integration extends IntegrationCore
{
    /**
     * @return bool
     */
    public function isNewContact(): bool
    {
        return $this->data['isNew'] ?? true;
    }

    /**
     * @return bool
     */
    public function getContactName()
    {
        return $this->data['contactName'] ?? false;
    }

    /**
     * @return |null
     */
    public function getResponsibleUserSid()
    {
        return $this->data['responsibleUser']['sid'] ?? null;
    }

    /**
     * @return |null
     */
    public function getResponsibleUser()
    {
        return $this->data['responsibleUser'] ?? null;
    }

    /**
     * @return array|null
     */
    public function get(): ?array
    {
        return $this->data;
    }

    /**
     * @param $data
     * @param $route
     * @return array|null
     */
    public function getData($data, $route): ?array
    {
        if (is_null($this->data))
            if (($response = $this->_serviceRequest($data, $route)) instanceof Response)
                if (is_array($response->body))
                    $this->data = $response->body;

        return $this->data;
    }

    /**
     * @param $data
     * @param $route
     * @return bool
     */
    public function sendData($data, $route): bool
    {
        return (bool)$this->_serviceRequest($data, $route);
    }
}