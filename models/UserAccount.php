<?php


namespace rushstart\user\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string|null $source_id
 * @property string $source_token
 * @property string|null $properties
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Identity $identity
 */
class UserAccount extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_auth';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getIdentity()
    {
        return $this->hasOne(Identity::class, ['id' => 'user_id']);
    }
}