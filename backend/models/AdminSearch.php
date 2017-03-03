<?php

namespace backend\models;

use Yii;
use backend\models\Admin;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AdminSearch extends Admin
{

    public function rules()
    {
        return [
            [['id', 'status', 'group', 'post_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Admin::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);


        /*$dataProvider->setSort([
            'attributes' => [
                'postName' => [
                    'desc' => ['post.name' => SORT_DESC],
                    'asc' => ['post.name' => SORT_ASC],
                ],
            ],
            ]);*/



        //$this->load($params);

        if (!($this->load($params) && $this->validate())) {
             $query->joinWith(['post']);
             return $dataProvider;
         }


        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'username' => $this->username,
            'group' => $this->group,
            'post_id' => $this->post_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
              ->andFilterWhere(['like', 'group', $this->group])
              ->andFilterWhere(['like', 'post_id', $this->post_id])
              ->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'password_hash', $this->password_hash])
              ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);


        return $dataProvider;
    }
	
	
}
