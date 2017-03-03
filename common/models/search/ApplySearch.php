<?php

namespace common\models\search;

use Yii;
use common\models\Apply;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ApplySearch extends Apply
{

    public function rules()
    {
        return [
            [['id', 'uid', 'category', 'product_id', 'create_time', 'is_del', 'app_id', 'agree_time'], 'integer'],
            [['uid', 'category', 'create_time', 'product_id', 'app_id'], 'safe'],
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
        $query = Apply::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    //'id' => SORT_DESC,
                    'id' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'category' => $this->category,
            'product_id' => $this->product_id,
            'app_id' => $this->app_id,
            'create_time' => $this->create_time,
        ]);

        //$query->andFilterWhere([($this->create_time_operand) ? $this->create_time_operand : '=', 'create_time_operand', ($this->create_time) ? strtotime($this->create_time) : null]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
              ->andFilterWhere(['like', 'category', $this->category])
              ->andFilterWhere(['like', 'product_id', $this->product_id])
              ->andFilterWhere(['like', 'app_id', $this->app_id]);

        return $dataProvider;
    }

}
