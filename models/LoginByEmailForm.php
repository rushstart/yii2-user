<?php


namespace rushstart\user\models;


use rushstart\user\models\auth\EmailAuth;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginByEmailForm extends Model
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
            $auth = $this->getAuth();

            if (!$auth || !$auth->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный Email или Пароль.');
            }
            if ($auth && !$this->getIdentity()) {
                $this->addError($attribute, 'Пользователь заблокирован.');
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

    /**
     * @return EmailAuth|null
     */
    public function getAuth()
    {
        if ($this->_auth === false) {
            $this->_auth = EmailAuth::findByEmail($this->email);
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
            return Yii::$app->user->login($this->getIdentity(), $this->rememberMe ? (Yii::$app->params['user.rememberMeDuration'] ?? 3600) : 0);
        }
        return false;
    }


}