<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\User\user;

/**
 * UserSearh represents the model behind the search form of `shop\entities\User\user`.
 */
class UserSearch extends Model
{
    public $id;
    public $status;
    public $created_at;
    public $username;
    public $email;

    public function rules()
    {
        return [
            [['id', 'status', 'created_at'], 'integer'],
            [['username', 'email'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = user::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
