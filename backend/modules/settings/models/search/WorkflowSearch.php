<?php

namespace backend\modules\settings\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\settings\models\Workflow;

/**
 * This is the model class for table "zcb_workflow".
 *
 * @property integer $id
 * @property integer $steps
 * @property string $workname
 * @property string $description
 * @property string $settings
 * @property integer $flag
 */
class WorkflowSearch extends Workflow
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['steps', 'flag'], 'integer'],
            [['workname', 'description', 'settings'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Workflow::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
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
            'steps' => $this->steps,
            'workname' => $this->workname,
            'description' => $this->description,
            'settings' => $this->settings,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'steps', $this->steps])
              ->andFilterWhere(['like', 'workname', $this->workname])
              ->andFilterWhere(['like', 'description', $this->description])
              ->andFilterWhere(['like', 'flag', $this->flag]);

        return $dataProvider;
    }

}
