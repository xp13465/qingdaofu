<?php

namespace common\models\search;

use Yii;
use common\models\FinanceProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FinanceProductSearch extends FinanceProduct
{

    public function rules()
    {
        return [
            [['id', 'uid', 'category', 'status', 'create_time', 'money'], 'integer'],
            [['category', 'code', 'money', 'term', 'rate', 'rate_cat', 'rebate'], 'safe'],
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
        $query = FinanceProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
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
            'category' => $this->category,
            'code' => $this->code,
            'money' => $this->money,
            'term' => $this->term,
            'rate' => $this->rate,
            'rate_cat' => $this->rate_cat,
            'rebate' => $this->rebate,
            'status' => $this->status,
        ]);

        //$query->andFilterWhere([($this->published_at_operand) ? $this->published_at_operand : '=', 'published_at', ($this->published_at) ? strtotime($this->published_at) : null]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
              ->andFilterWhere(['like', 'category', $this->category])
              ->andFilterWhere(['like', 'code', $this->code])
              ->andFilterWhere(['like', 'money', $this->money])
              ->andFilterWhere(['like', 'term', $this->term])
              ->andFilterWhere(['like', 'rate', $this->rate])
              ->andFilterWhere(['like', 'rate_cat', $this->rate_cat])
              ->andFilterWhere(['like', 'rebate', $this->rebate])
              ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}
