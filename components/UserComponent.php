<?php


namespace app\modules\user\components;


use app\modules\user\models\Identity;
use yii\web\IdentityInterface;
use yii\web\User;

class UserComponent extends User
{

    /**
     * {@inheritdoc}
     * @param IdentityInterface|Identity $identity the user identity (which should already be authenticated)
     */
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        $identity->setAttribute('logged_in_at', time());
        $identity->save(false, ['logged_in_at']);
        parent::afterLogin($identity, $cookieBased, $duration);
    }
}