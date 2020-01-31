<?php


namespace rushstart\user\models\auth;


use rushstart\user\ClientInterface;
use rushstart\user\models\UserAccount;
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
class EmailAccount extends UserAccount implements ClientInterface
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
                $this->andWhere(['source' => EmailAccount::SOURCE]);
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

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->source = $id;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        // TODO: Implement setTitle() method.
    }

    /**
     * @inheritDoc
     */
    public function getUserAttributes()
    {
        // TODO: Implement getUserAttributes() method.
    }

    /**
     * @inheritDoc
     */
    public function getViewOptions()
    {
        // TODO: Implement getViewOptions() method.
    }
}