<?php

namespace common\models\search;

use Yii;
use common\models\CreditorProduct;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CreditorProductSearch extends CreditorProduct
{

    public function rules()
    {
        return [
            [['id', 'uid', 'category', 'create_time', 'money', 'progress_status'], 'integer'],
            [['uid','category', 'code', 'money'], 'safe'],
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
        $query = CreditorProduct::find();

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
            'uid' => $this->uid,
            'category' => $this->category,
            'code' => $this->code,
            'money' => $this->money,
            'progress_status' => $this->progress_status,
        ]);


        $query->andFilterWhere(['like', 'uid', $this->uid])
              ->andFilterWhere(['like', 'category', $this->category])
              ->andFilterWhere(['like', 'code', $this->code])
              ->andFilterWhere(['like', 'money', $this->money])
              ->andFilterWhere(['like', 'progress_status', $this->progress_status]);

        return $dataProvider;
    }

}
