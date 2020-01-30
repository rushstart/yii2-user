<?php


namespace rushstart\user\models\auth;


use rushstart\user\models\UserAuth;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class EmailAuth
 * Auth via email and password
 *
 * @property string $email
 * @property string $password write-only password
 */
class EmailAuth extends UserAuth
{
    const SOURCE = 'email';

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return new class(get_called_class()) extends ActiveQuery {
            public function init()
            {
                parent::init();
                $this->andWhere(['source' => EmailAuth::SOURCE]);
            }
        };
    }

    /**
     * Finds by email
     * @param string $email
     * @return array|ActiveRecord|static
     */
    public static function findByEmail(string $email)
    {
        return self::find()->where(['source_id' => $email])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'source_token'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->source = self::SOURCE;
        }
        return parent::beforeSave($insert);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password)
    {
        return Yii::$app->security->validatePassword($password, $this->source_token);
    }

    public function getEmail()
    {
        return $this->source_id;
    }

    public function setEmail($value)
    {
        $this->source_id = $value;
    }

    /**
     * @param $value
     * @throws Exception
     */
    public function setPassword($value)
    {
        $this->source_token = Yii::$app->security->generatePasswordHash($value);
    }
}