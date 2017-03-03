<?php

namespace common\models\search;

use Yii;
use common\models\Certification;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CertificationSearch extends Certification
{

    public function rules()
    {
        return [
            [['id', 'uid', 'category', 'state', 'create_time', 'managersnumber'], 'integer'],
            [['name', 'address', 'email', 'mobile', 'cardno', 'contact'], 'safe'],
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
        $query = Certification::find();

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
            'name' => $this->name,
            'category' => $this->category,
            'address' => $this->address,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'cardno' => $this->cardno,
            'contact' => $this->contact,
            'state' => $this->state,
        ]);

        //$query->andFilterWhere([($this->published_at_operand) ? $this->published_at_operand : '=', 'published_at', ($this->published_at) ? strtotime($this->published_at) : null]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'category', $this->category])
              ->andFilterWhere(['like', 'address', $this->address])
              ->andFilterWhere(['like', 'mobile', $this->mobile])
              ->andFilterWhere(['like', 'contact', $this->contact])
              ->andFilterWhere(['like', 'cardno', $this->cardno]);

        return $dataProvider;
    }

}
