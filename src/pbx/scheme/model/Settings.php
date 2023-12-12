<?php


namespace app\src\pbx\scheme\model;


use app\src\models\User;
use app\src\models\Model;

/**
 * Class Settings
 * @package app\src\pbx\scheme\model
 */
class Settings extends Model
{
    public bool $call_to_resp = false;
    public bool $show_cname = false;
    public array $welcome_message = [];
    public array $list_of_enabled_responsible_users;
    public array $list_of_disabled_responsible_user;

    /**
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


