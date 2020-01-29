<?php

namespace rushstart\user\components;


use yii\web\User;

/**
 * UserComponent is the class for the `user` application component that manages the user authentication status.
 */
class UserComponent extends User
{

    /**
     * {@inheritdoc}
     * Note that you must configure "authManager" application component in order to use this method.
     * Otherwise it will always return true if the user is authorized.
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if($this->getAccessChecker() === null && !$this->isGuest){
            return true;
        }
        return parent::can($permissionName, $params, $allowCaching);
    }

    protected function afterLogin($identity, $cookieBased, $duration)
    {
        $identity->logged_in_at = time();
        $identity->save(false,['logged_in_at']);
        parent::afterLogin($identity, $cookieBased, $duration);
    }

}