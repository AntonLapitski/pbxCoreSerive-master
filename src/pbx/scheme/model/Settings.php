<?php


namespace app\src\pbx\scheme\model;


use app\src\models\User;
use app\src\models\Model;

/**
 * Class Settings
 * @property bool $call_to_resp
 * @property bool $show_cname
 * @property array $welcome_message
 * @property array $list_of_enabled_responsible_users
 * @property array $list_of_disabled_responsible_user
 * @package app\src\pbx\scheme\model
 */
class Settings extends Model
{
    /**
     * звонок ответственному
     *
     * @var bool
     */
    public bool $call_to_resp = false;

    /**
     * показать имя
     *
     * @var bool
     */
    public bool $show_cname = false;

    /**
     * приветственное сообщение
     *
     * @var array
     */
    public array $welcome_message = [];

    /**
     * лист разрешенных ответственных юзеров
     *
     * @var array
     */
    public array $list_of_enabled_responsible_users;

    /**
     * лист неразрешенных ответственных юзеров
     *
     * @var array
     */
    public array $list_of_disabled_responsible_user;

    /**
     * является ли разрешенным ответственнный для звонка для текущего юзера
     *
     * @param User $user
     * @return bool
     */
    public function isEnabledResponsibleCallForCurrentUser(User $user): bool
    {
        if (empty($this->list_of_enabled_responsible_users ?? []))
            return true;

        return in_array($user->sid, $this->list_of_enabled_responsible_users);
    }

    /**
     * является ли неразрешенным ответственнный для звонка для текущего юзера
     *
     * @param User $user
     * @return bool
     */
    public function isNotDisabledResponsibleCallForCurrentUser(User $user): bool
    {
        if (empty($this->list_of_disabled_responsible_user ?? []))
            return true;

        return !in_array($user->sid, $this->list_of_disabled_responsible_user);
    }
}


