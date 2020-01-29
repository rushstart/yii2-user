<?php


namespace rushstart\user\models;


use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @var Identity
     */
    private $_identity = false;
    private $_auth = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $identity = $this->getIdentity();
            $auth = $this->getAuth();

            if (!$auth || !$identity || !Yii::$app->security->validatePassword($this->password, $auth->source_token)) {
                $this->addError($attribute, 'Неверный Email или Пароль.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    public function getAuth()
    {
        if ($this->_auth === false) {
            $this->_auth = UserAuth::findOne(['source' => 'email', 'source_id' => $this->email]);
        }
        return $this->_auth;
    }

    /**
     * Finds identity by email
     *
     * @return Identity|null
     */
    public function getIdentity()
    {
        if ($this->_identity === false) {
            if ($this->getAuth() === null) {
                $this->_identity = null;
            }
            $this->_identity = Identity::findIdentity($this->getAuth()->user_id);
        }
        return $this->_identity;
    }

    /**
     * Logs in a user using the provided email and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getIdentity(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }


}