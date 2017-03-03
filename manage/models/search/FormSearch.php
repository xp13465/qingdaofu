<?php

namespace manage\models\search;

use Yii;
use manage\models\Form;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "zcb_form_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class FormSearch extends Form
{

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['id', 'category_id', 'pay_time', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['code','name', 'department', 'unit', 'bank', 'bank_account', 'item', 'item_type', 'pay_type', 'pay_name', 'cny', 'remark'], 'safe'],
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
        $query = Form::find();

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
            'category_id' => $this->category_id,
            'code' => $this->code,
            'name' => $this->name,
            'department' => $this->department,
            'unit' => $this->unit,
            'bank' => $this->bank,
            'bank_account' => $this->bank_account,
            'item' => $this->item,
            'item_type' => $this->item_type,
            'pay_type' => $this->pay_type,
            'pay_name' => $this->pay_name,
            'cny' => $this->cny,
            'remark' => $this->remark,

        ]);

        $query->andFilterWhere(['like', 'category_id', $this->category_id])
              ->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'code', $this->code])
              ->andFilterWhere(['like', 'department', $this->department])
              ->andFilterWhere(['like', 'unit', $this->unit])
              ->andFilterWhere(['like', 'bank', $this->bank])
              ->andFilterWhere(['like', 'bank_account', $this->bank_account])
              ->andFilterWhere(['like', 'item', $this->item])
              ->andFilterWhere(['like', 'item_type', $this->item_type])
              ->andFilterWhere(['like', 'pay_type', $this->pay_type])
              ->andFilterWhere(['like', 'pay_name', $this->pay_name])
              ->andFilterWhere(['like', 'cny', $this->cny])
              ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }





}
