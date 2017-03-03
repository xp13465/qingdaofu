<?php

namespace common\models\search;

use Yii;
use common\models\FormCategory;
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
class FormCategorySearch extends FormCategory
{

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['id', 'steps' ,'created_at', 'updated_at'], 'integer'],
            [['name', 'type', 'description'], 'safe'],
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
        $query = FormCategory::find();

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
            'name' => $this->name,
            'type' => $this->type,
            'steps' => $this->steps,
            'description' => $this->description,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'type', $this->type])
              ->andFilterWhere(['like', 'steps', $this->steps])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }





}
