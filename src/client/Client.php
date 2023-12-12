<?php

namespace app\src\client;

use app\src\models\Company;
use app\src\models\Twilio;
use app\src\event\request\strategy\RequestInterface;
use yii\web\ForbiddenHttpException;


/**
 * @property int $id
 * @property string $name
 * @property Company $company
 * @property UserList $userList
 * @property Twilio $twilio
 **/
class Client
{
    public int $id;
    public Company $company;
    public string $name;
    public UserList $userList;
    public Twilio $twilio;


    /**
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
     * @throws ForbiddenHttpException
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

