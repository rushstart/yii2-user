<?php

namespace rushstart\user\models;


use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $logged_in_at
 *
 * @property string $password write-only password
 *
 */
class User extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const AUTH_EMAIL = 'email';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'активен',
            self::STATUS_DELETED => 'отключен',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): ActiveQuery
    {
        return new UserQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
