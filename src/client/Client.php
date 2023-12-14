<?php

namespace app\src\client;

use app\src\models\Company;
use app\src\models\Twilio;
use app\src\event\request\strategy\RequestInterface;
use yii\web\ForbiddenHttpException;


/**
 * Class Client
 * Класс клиента
 *
 * @property int $id
 * @property string $name
 * @property Company $company
 * @property UserList $userList
 * @property Twilio $twilio
 * @package app\src\client
 **/
class Client
{
    /**
     * айди клиента
     *
     * @var int
     */
    public int $id;

    /**
     * компания
     *
     * @var int
     */
    public Company $company;

    /**
     * имя клиента
     *
     * @var int
     */
    public string $name;

    /**
     * список юзеров
     *
     * @var int
     */
    public UserList $userList;

    /**
     * объект твилио
     *
     * @var int
     */
    public Twilio $twilio;


    /**
     * Class Client constructor
     *
     * @param RequestInterface $request
     * @throws ForbiddenHttpException
     */
    public function __construct(RequestInterface $request)
    {
        $this->company = self::getCompany($request);
        $this->id = $this->company->id;
        $this->name = $this->company->name;
        $this->twilio = $this->company->twilio;
        $this->userList = new UserList(['list' => $this->company->users]);
    }

    /**
     * получить компанию
     *
     * @throws ForbiddenHttpException
     * @return string
     */
    private static function getCompany(RequestInterface $request): ?Company
    {
        if (isset($request->CompanySid))
            if ($company = Company::findOne(['sid' => $request->CompanySid]))
                return $company;

        if (isset($request->AccountSid))
            if ($twilio = Twilio::findOne(['sid' => $request->AccountSid]))
                return $twilio->company;

        throw new ForbiddenHttpException('Company not registered', 403);
    }
}

