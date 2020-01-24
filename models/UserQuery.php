<?php

namespace app\modules\user\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends ActiveQuery
{
    /**
     * @return ActiveQuery
     */
    public function active()
    {
        $this->andWhere(['status' => User::STATUS_ACTIVE]);
        return $this;
    }
}
