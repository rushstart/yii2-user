<?php


namespace rushstart\user\models;


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
 * @property User $user
 */
class UserAuth extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_auth';
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}