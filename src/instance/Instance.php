<?php


namespace app\src\instance;


use app\src\models\User;
use app\src\event\Event;
use app\src\instance\config\src\integration\Integration;


/**
 * Class Instance
 * @package app\src\instance
 */
abstract class Instance implements InstanceInterface
{
    use InstanceBuilder;

    /**
     * @param null $name
     * @return string|null
     */
    public function getCallerName($name = null): ?string
    {
        $cleaner = function ($string) {
            $string = str_replace(
                [':', '!', '#', '$', '%', '&', '*', '+', '-', '=', '?', '^', '`', '{', '|', '}', '~', '@', '.', '[', ']', ' ', ',', '(', ')', ' '],
                '_',
                $string
            );
            return substr($string, 0, 14);
        };

        $isCorrect = function ($string) {
            return (
                !is_numeric(str_replace("_", "", $string))
                &&
                mb_detect_encoding($string, 'UTF-8', true)
                &&
                !(boolean)preg_match("/[а-я]+/i", $string)
            );
        };

        if ($name){
            if ($isCorrect($result = $cleaner($name)))
                return $result;
        } else  {
            if (($this->config->integration ?? false) instanceof Integration)
                if ($name = $this->config->integration->getContactName())
                    if ($isCorrect($result = $cleaner($name)))
                        return $result;

            if (isset($this->event->request->CallerName))
                if ($isCorrect($result = $cleaner(($this->event->request->CallerName))))
                    return $result;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isInBlackList(): bool
    {
        return is_numeric(array_search(
            $this->event->request->From,
            array_column($this->config->blacklist->list ?? [], 'number')
        ));
    }

    /**
     * @return User
     */
    public function getReferTarget(): User
    {
        if ($this->event->request->ReferTransferTarget ?? false)
            return $this->client->userList->get($this->event->request->ReferTransferTarget);
    }

    /**
     * @return mixed
     */
    protected function getCallInitSourceFromTwilio()
    {
        $callData = \Yii::$app->twilio->getCallData($this->event->request->CallSid);
        $direction = $this->event->request->Direction;

        if ($direction == Event::DIRECTION_INCOMING || $direction == Event::DIRECTION_INTERNAL)
            return $callData['To'];
        if ($direction == Event::DIRECTION_OUTGOING)
            return $callData['From'];
    }
}