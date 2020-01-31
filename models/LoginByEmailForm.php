<?php


namespace rushstart\user\models;


use rushstart\user\models\auth\EmailAccount;
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
                $this->addError($attribute, Yii::t('user','Invalid email or password'));
            }
            if ($auth && !$this->getIdentity()) {
                $this->addError($attribute, Yii::t('user','Your account has been blocked'));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'Email'),
            'password' => Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me'),
        ];
    }

    /**
     * @return EmailAccount|null
     */
    public function getAuth()
    {
        if ($this->_auth === false) {
            $this->_auth = EmailAccount::findByEmail($this->email);
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