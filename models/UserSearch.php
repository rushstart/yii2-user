<?php


namespace app\modules\user\models;


use app\extensions\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 */
class UserSearch extends User
{

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['status'], 'default', 'value' => ActiveRecord::STATUS_ACTIVE],
            [['id'], 'integer'],
            [['email', 'name', 'status'], 'safe'],
        ];
    }


    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {

        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name]);


        return $dataProvider;
    }
}