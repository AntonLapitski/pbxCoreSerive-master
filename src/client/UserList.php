<?php


namespace app\src\client;

use app\src\models\User;
use app\src\models\Model;

/**
 * @property User[] $list;
 *
 */
class UserList extends Model
{
    public array $list;

    /**
     * @param $target
     * @return bool
     */
    public function getReferTarget($target): bool|array
    {
        if (null == $target)
            return false;

        $user = $this->get($target);

        return strlen($target) > 5
            ? ['number' => $target]
            : [
                'sid' => $user->sid,
                'sip' => $user->sip,
                'number' => $user->mobile_number,
                'settings' => $user->settings['direct_call_settings'] ?? null
            ];
    }

    public
/**
 * @param $target
 * @return User
 */
function get($target): User
    {
        if (strlen($target) < 5)
            return $this->find(['sip' => $target]);
        if (is_numeric(str_replace('+', '', $target)))
            return $this->find(['mobile_number' => $target]);

        return $this->find(['sid' => $target]);
    }

    private
/**
 * @param $condition
 * @return User
 */
function find($condition): User
    {
        $property = key($condition);
        $userList = array_column($this->list, $property);
        $id = array_search($condition[$property], $userList);
        if (false !== $id)
            return $this->list[$id];

        return new User();
    }

}