<?php


namespace rushstart\user\models;

use rushstart\user\models\auth\EmailAuth;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Signup form
 */
class SignupByEmailForm extends Model
{
    public $email;
    public $name;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'emailValidate'],
            //
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            //
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function emailValidate($attribute, $params)
    {
        if (EmailAuth::findByEmail($this->$attribute) !== null) {
            $this->addError($attribute, 'Такой email уже используется');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful
     * @throws Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new Identity([
            'name' => $this->name,
        ]);
        $user->generateAuthKey();

        $transaction = User::getDb()->beginTransaction();

        if ($user->save()) {
            $auth = new EmailAuth([
                'user_id' => $user->id,
                'email' => $this->email,
                'password' => $this->password,
            ]);
            if ($auth->save()) {
                $transaction->commit();
                Yii::$app->user->login($user, (Yii::$app->params['user.rememberMeDuration'] ?? 3600) ?? 0);
                return true;
            } else {
                /** @noinspection PhpComposerExtensionStubsInspection */
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', 'Unable to save {client} account: {errors}', [
                        'client' => 'email',
                        'errors' => json_encode($auth->getErrors()),
                    ]),
                ]);
            }
        }
        return false;

    }

}