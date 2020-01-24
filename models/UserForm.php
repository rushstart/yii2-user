<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the form class for ActiveRecord "User".
 *
 */
class UserForm extends User
{
    public $newPassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
        if ($this->newPassword && Yii::$app->user->can('change_user_password')) {
            $this->setPassword($this->newPassword);
        }
        return parent::beforeSave($insert);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws /yii/base/Exception on failure.
     */
    protected function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

}
